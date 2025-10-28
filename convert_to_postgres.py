#!/usr/bin/env python3
"""
Convertidor de SQL MariaDB/MySQL a PostgreSQL
Optimizado para archivos grandes (70K+ líneas)
"""

import re
import sys

def convert_sql_to_postgres(input_file, output_file):
    """
    Convierte SQL de MariaDB/MySQL a PostgreSQL
    """
    print(f"[*] Iniciando conversión: {input_file}")
    print(f"[*] Archivo de salida: {output_file}")
    
    with open(input_file, 'r', encoding='utf-8') as f_in:
        with open(output_file, 'w', encoding='utf-8') as f_out:
            # Escribir header de PostgreSQL
            f_out.write("-- PostgreSQL dump converted from MariaDB/MySQL\n")
            f_out.write("-- Compatible with Neon PostgreSQL\n")
            f_out.write("-- Generated automatically\n\n")
            
            line_count = 0
            skip_mode = False
            current_table = None
            
            for line in f_in:
                line_count += 1
                
                # Progress indicator
                if line_count % 10000 == 0:
                    print(f"[*] Procesadas {line_count} líneas...")
                
                # Omitir líneas de configuración de MySQL
                if line.strip().startswith('/*!'):
                    continue
                
                if line.strip().startswith('--'):
                    f_out.write(line)
                    continue
                
                # Convertir CREATE DATABASE
                if 'CREATE DATABASE' in line:
                    # Omitir en PostgreSQL, crear schema si es necesario
                    continue
                
                # Convertir USE database
                if line.strip().startswith('USE `'):
                    continue
                
                # Convertir CREATE TABLE
                if 'CREATE TABLE IF NOT EXISTS' in line:
                    # Extraer nombre de tabla
                    match = re.search(r'`(\w+)`', line)
                    if match:
                        current_table = match.group(1)
                        print(f"  -> Procesando tabla: {current_table}")
                    
                    # Convertir sintaxis
                    line = line.replace('`', '"')  # Backticks a comillas
                    line = line.replace('/*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */', '')
                    f_out.write(line)
                    continue
                
                # Convertir tipos de datos
                line = re.sub(r'\bauto_increment\b', 'AUTO_INCREMENT', line, flags=re.IGNORECASE)
                line = re.sub(r'\bBIGINT\(20\)\s+unsigned', 'BIGINT', line, flags=re.IGNORECASE)
                line = re.sub(r'\bINT\(11\)\s+unsigned', 'INTEGER', line, flags=re.IGNORECASE)
                line = re.sub(r'\bINT\(11\)', 'INTEGER', line, flags=re.IGNORECASE)
                line = re.sub(r'\bTINYINT\(3\)\s+unsigned', 'SMALLINT', line, flags=re.IGNORECASE)
                line = re.sub(r'\bVARCHAR\(255\)', 'VARCHAR(255)', line, flags=re.IGNORECASE)
                line = re.sub(r'\bTIMESTAMP\s+NULL\s+DEFAULT\s+NULL', 'TIMESTAMP NULL', line, flags=re.IGNORECASE)
                line = re.sub(r'\bTIMESTAMP\s+NULL', 'TIMESTAMP NULL', line, flags=re.IGNORECASE)
                line = re.sub(r'\bCURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP', line)
                line = re.sub(r'\bKEY\s+`', 'KEY "', line, flags=re.IGNORECASE)
                line = re.sub(r'\bCONSTRAINT\s+`', 'CONSTRAINT "', line, flags=re.IGNORECASE)
                line = re.sub(r'\bREFERENCES\s+`', 'REFERENCES "', line, flags=re.IGNORECASE)
                
                # Convertir backticks a comillas
                line = line.replace('`', '"')
                
                # Convertir ENGINE
                if 'ENGINE=InnoDB' in line or 'ENGINE=MyISAM' in line:
                    line = re.sub(r'ENGINE=\w+\s+', '', line, flags=re.IGNORECASE)
                    line = re.sub(r'\s*DEFAULT\s+CHARSET.*?\s*;', ';', line, flags=re.IGNORECASE)
                
                # Convertir COLLATE
                line = re.sub(r'\s+COLLATE\s+\S+', '', line, flags=re.IGNORECASE)
                
                # Convertir DEFAULT CHARSET
                if 'DEFAULT CHARSET' in line:
                    continue
                
                # Convertir DELETE FROM
                if 'DELETE FROM' in line:
                    line = line.replace('`', '"')
                    # En PostgreSQL, usar TRUNCATE es más eficiente
                    match = re.search(r'DELETE\s+FROM\s+"(\w+)"', line, flags=re.IGNORECASE)
                    if match:
                        table_name = match.group(1)
                        line = f'TRUNCATE TABLE "{table_name}" RESTART IDENTITY CASCADE;\n'
                
                # Convertir INSERT INTO
                if 'INSERT INTO' in line:
                    line = line.replace('`', '"')
                
                # Convertir PRIMARY KEY
                if 'PRIMARY KEY' in line:
                    line = re.sub(r'AUTO_INCREMENT', '', line, flags=re.IGNORECASE)
                
                # Omitir líneas de motor y charset específicas de MySQL
                if 'AUTO_INCREMENT=' in line or 'CHARSET=' in line or 'COLLATE=' in line:
                    continue
                
                # Limpiar líneas en blanco excesivas
                if line.strip():
                    f_out.write(line)
                elif f_out.tell() > 0:  # No escribir líneas en blanco al inicio
                    f_out.write(line)
    
    print(f"\n[✓] Conversión completada!")
    print(f"[✓] Total de líneas procesadas: {line_count}")
    print(f"[✓] Archivo PostgreSQL listo: {output_file}")

if __name__ == '__main__':
    input_file = 'mline.sql'
    output_file = 'mline_postgres.sql'
    
    try:
        convert_sql_to_postgres(input_file, output_file)
    except FileNotFoundError:
        print(f"[!] Error: No se encontró el archivo {input_file}")
        sys.exit(1)
    except Exception as e:
        print(f"[!] Error durante la conversión: {str(e)}")
        sys.exit(1)
