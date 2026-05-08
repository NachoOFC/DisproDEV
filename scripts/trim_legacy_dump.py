#!/usr/bin/env python3
"""Recorta un dump SQL enorme a un dataset pequeño.

Pensado para archivos estilo `mline_postgres.sql` (generados desde HeidiSQL),
que mezclan comentarios + TRUNCATE + INSERT multi-row.

Salida:
- Mantiene comentarios y encabezados.
- Elimina bloques `CREATE TABLE` (suelen venir con sintaxis MySQL no válida).
- Mantiene `TRUNCATE TABLE ...;`.
- Recorta cada `INSERT INTO ... VALUES (...)` a N tuplas por tabla.

Nota:
- Esto NO intenta adaptar el esquema; solo recorta datos.
- Requiere que las tablas ya existan en PostgreSQL con columnas compatibles.
"""

from __future__ import annotations

import argparse
import re
from pathlib import Path


INSERT_START_RE = re.compile(r'^INSERT\s+INTO\s+"(?P<table>[^"]+)"\s*\(', re.IGNORECASE)
TRUNCATE_RE = re.compile(r'^TRUNCATE\s+TABLE\s+"(?P<table>[^"]+)"\b', re.IGNORECASE)
CREATE_TABLE_RE = re.compile(r'^CREATE\s+TABLE\b', re.IGNORECASE)


def find_terminator_outside_strings(text: str, in_string: bool) -> tuple[int, bool]:
    """Find ';' terminator outside single-quoted strings.

    Returns (index, new_in_string). index == -1 if not found.
    """
    i = 0
    while i < len(text):
        ch = text[i]
        if in_string:
            if ch == "'":
                # escaped ''
                if i + 1 < len(text) and text[i + 1] == "'":
                    i += 2
                    continue
                in_string = False
            i += 1
            continue

        if ch == "'":
            in_string = True
            i += 1
            continue

        if ch == ';':
            return i, in_string

        i += 1
    return -1, in_string


def iter_insert_tuples(values_sql: str):
    """Yields tuple strings including parentheses: '(...)' from the VALUES tail.

    Robust against commas/parentheses inside single-quoted strings.
    Assumes VALUES tail contains only tuples separated by commas and ends before ';'.
    """
    in_string = False
    i = 0
    start = None
    depth = 0
    while i < len(values_sql):
        ch = values_sql[i]

        if in_string:
            if ch == "'":
                # handle escaped ''
                if i + 1 < len(values_sql) and values_sql[i + 1] == "'":
                    i += 2
                    continue
                in_string = False
            i += 1
            continue

        if ch == "'":
            in_string = True
            i += 1
            continue

        if ch == '(':
            if depth == 0:
                start = i
            depth += 1
            i += 1
            continue

        if ch == ')':
            if depth > 0:
                depth -= 1
                if depth == 0 and start is not None:
                    yield values_sql[start : i + 1]
                    start = None
            i += 1
            continue

        i += 1


def trim_insert_block(block: str, limit: int) -> tuple[str, int]:
    """Trim an INSERT statement block to first N tuples.

    Returns (new_block, tuples_kept)
    """
    # Find the VALUES keyword and split head/tail
    m = re.search(r'\bVALUES\b', block, flags=re.IGNORECASE)
    if not m:
        return block, 0

    head = block[: m.end()]
    tail = block[m.end() :]

    # strip ending semicolon (keep it later)
    tail_no_semicolon = tail
    semicolon_pos = tail_no_semicolon.rfind(';')
    if semicolon_pos != -1:
        tail_no_semicolon = tail_no_semicolon[:semicolon_pos]

    tuples = []
    for t in iter_insert_tuples(tail_no_semicolon):
        tuples.append(t)
        if len(tuples) >= limit:
            break

    if not tuples:
        return block, 0

    # Rebuild nicely with one tuple per line (compact but readable)
    new_lines = [head.rstrip()]
    for idx, t in enumerate(tuples):
        suffix = ',' if idx < len(tuples) - 1 else ';'
        new_lines.append(f"\t{t}{suffix}")

    new_block = "\n".join(new_lines) + "\n"
    return new_block, len(tuples)


def trim_dump(input_path: Path, output_path: Path, default_limit: int, per_table: dict[str, int]) -> None:
    skip_table_def = False
    in_insert = False
    insert_lines: list[str] = []
    current_insert_table: str | None = None
    insert_in_string = False

    kept_counts: dict[str, int] = {}

    with input_path.open('r', encoding='utf-8', errors='ignore') as fin, output_path.open(
        'w', encoding='utf-8'
    ) as fout:
        for raw_line in fin:
            line = raw_line

            # A single raw line may contain multiple statements: keep processing remainders.
            while True:
                # 1) If currently capturing an INSERT, keep collecting until a terminator ';'
                #    that is outside single-quoted strings.
                if in_insert:
                    term_idx, insert_in_string = find_terminator_outside_strings(line, insert_in_string)

                    if term_idx == -1:
                        insert_lines.append(line)
                        break

                    # Split at the statement terminator and keep the remainder for re-processing.
                    insert_lines.append(line[: term_idx + 1])
                    block = ''.join(insert_lines)
                    limit = per_table.get(current_insert_table or '', default_limit)
                    trimmed, kept = trim_insert_block(block, limit)
                    fout.write(trimmed)
                    if current_insert_table:
                        kept_counts[current_insert_table] = kept_counts.get(current_insert_table, 0) + kept

                    # Reset INSERT capture
                    in_insert = False
                    insert_lines = []
                    current_insert_table = None
                    insert_in_string = False

                    # Process the remainder after ';' (could start a new statement)
                    line = line[term_idx + 1 :]
                    if line.strip() == '':
                        break
                    continue

                # 2) Skip CREATE TABLE definitions (the source dump may omit proper ');')
                if CREATE_TABLE_RE.match(line):
                    skip_table_def = True
                    break

                if skip_table_def:
                    # Stop skipping when we reach the data section markers
                    if TRUNCATE_RE.match(line) or INSERT_START_RE.match(line):
                        skip_table_def = False
                        continue

                    # Keep only comments/blank lines while skipping table defs
                    if line.lstrip().startswith('--') or line.strip() == '':
                        fout.write(line)
                    break

                # 3) Pass-through TRUNCATE lines
                if TRUNCATE_RE.match(line):
                    fout.write(line)
                    break

                # 4) Start capturing INSERT
                m_ins = INSERT_START_RE.match(line)
                if m_ins:
                    in_insert = True
                    current_insert_table = m_ins.group('table')
                    insert_lines = []
                    insert_in_string = False
                    continue

                # 5) Keep comments and blank lines
                if line.lstrip().startswith('--') or line.strip() == '':
                    fout.write(line)
                    break

                # Everything else is ignored (LOCK TABLES, KEY definitions, etc.)
                break

    # Print summary to stdout
    if kept_counts:
        print("[ok] Recorte completado. Filas aproximadas por tabla (tuplas kept):")
        for table, count in sorted(kept_counts.items(), key=lambda x: (-x[1], x[0])):
            print(f"  - {table}: {count}")
    else:
        print("[warn] No se detectaron INSERT para recortar.")


def parse_kv_list(items: list[str]) -> dict[str, int]:
    result: dict[str, int] = {}
    for item in items:
        if '=' not in item:
            raise ValueError(f"Formato inválido '{item}'. Usa tabla=numero")
        k, v = item.split('=', 1)
        result[k.strip()] = int(v.strip())
    return result


def main() -> int:
    ap = argparse.ArgumentParser(description="Recorta un dump SQL a pocas filas por tabla")
    ap.add_argument('--input', default='mline_postgres.sql', help='Archivo SQL de entrada')
    ap.add_argument('--output', default='mline_postgres_trimmed.sql', help='Archivo SQL de salida')
    ap.add_argument('--limit', type=int, default=10, help='Tuplas máximas por tabla (default)')
    ap.add_argument(
        '--table-limit',
        action='append',
        default=[],
        help='Override por tabla: e.g. --table-limit requerimientos=25 (repetible)',
    )

    args = ap.parse_args()
    input_path = Path(args.input)
    output_path = Path(args.output)

    per_table = parse_kv_list(args.table_limit)

    if not input_path.exists():
        raise SystemExit(f"No existe: {input_path}")

    trim_dump(input_path, output_path, args.limit, per_table)
    print(f"\nSalida: {output_path}")
    return 0


if __name__ == '__main__':
    raise SystemExit(main())
