#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Convertidor de SQL MySQL a PostgreSQL
Uso: python3 convert_mysql_to_postgresql.py input.sql output.sql
"""

import sys
import re

def convert_mysql_to_postgresql(sql_content):
    """Convierte SQL de MySQL a PostgreSQL"""
    
    # 1. Cambiar CREATE DATABASE a CREATE SCHEMA
    sql_content = re.sub(
        r'CREATE DATABASE IF NOT EXISTS `(.+?)`.*?;',
        r'CREATE SCHEMA IF NOT EXISTS \1;',
        sql_content,
        flags=re.IGNORECASE | re.DOTALL
    )
    
    # 2. Cambiar USE a SET schema
    sql_content = re.sub(
        r'USE `(.+?)`;',
        r'SET search_path TO \1;',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # 3. Cambiar AUTO_INCREMENT a SERIAL/BIGSERIAL
    sql_content = re.sub(
        r'AUTO_INCREMENT',
        'GENERATED ALWAYS AS IDENTITY',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # 4. Cambiar backticks a comillas
    sql_content = sql_content.replace('`', '"')
    
    # 5. Cambiar ENGINE=InnoDB DEFAULT CHARSET a encoding
    sql_content = re.sub(
        r'\s*ENGINE\s*=\s*InnoDB.*?(?=;|\s*\))',
        '',
        sql_content,
        flags=re.IGNORECASE
    )
    
    sql_content = re.sub(
        r'\s*DEFAULT\s+CHARSET\s*=\s*\w+.*?(?=;|\s*\))',
        '',
        sql_content,
        flags=re.IGNORECASE
    )
    
    sql_content = re.sub(
        r'\s*COLLATE\s*=\s*\w+.*?(?=;|\s*\))',
        '',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # 6. Cambiar comentarios MySQL a PostgreSQL
    sql_content = re.sub(
        r'/\*!\d+\s+(.*?)\*/;?',
        r'-- \1',
        sql_content
    )
    
    # 7. Cambiar tipos de datos
    # BIGINT(20) UNSIGNED -> BIGINT
    sql_content = re.sub(
        r'BIGINT\(\d+\)\s+UNSIGNED',
        'BIGINT',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # INT UNSIGNED -> INTEGER
    sql_content = re.sub(
        r'INT\s+UNSIGNED',
        'INTEGER',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # TINYINT -> SMALLINT
    sql_content = re.sub(
        r'TINYINT\(\d+\)',
        'SMALLINT',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # TIMESTAMP -> TIMESTAMP WITHOUT TIME ZONE
    sql_content = re.sub(
        r'TIMESTAMP',
        'TIMESTAMP WITHOUT TIME ZONE',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # 8. Cambiar DELETE FROM ... sin WHERE a TRUNCATE
    sql_content = re.sub(
        r'DELETE FROM (\w+);',
        r'TRUNCATE TABLE \1 CASCADE;',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # 9. Remover AUTO_INCREMENT=X al final de CREATE TABLE
    sql_content = re.sub(
        r',?\s*AUTO_INCREMENT\s*=\s*\d+',
        '',
        sql_content,
        flags=re.IGNORECASE
    )
    
    # 10. Cambiar PRIMARY KEY (id) ... AUTO_INCREMENT
    sql_content = re.sub(
        r'`(\w+)`\s+BIGINT\(\d+\)\s+UNSIGNED\s+NOT NULL\s+AUTO_INCREMENT',
        r'"\1" BIGSERIAL',
        sql_content,
        flags=re.IGNORECASE
    )
    
    sql_content = re.sub(
        r'`(\w+)`\s+INT\(\d+\)\s+UNSIGNED\s+NOT NULL\s+AUTO_INCREMENT',
        r'"\1" SERIAL',
        sql_content,
        flags=re.IGNORECASE
    )
    
    return sql_content

def main():
    if len(sys.argv) != 3:
        print("Uso: python3 convert_mysql_to_postgresql.py input.sql output.sql")
        sys.exit(1)
    
    input_file = sys.argv[1]
    output_file = sys.argv[2]
    
    try:
        # Leer archivo MySQL
        print(f"Leyendo {input_file}...")
        with open(input_file, 'r', encoding='utf-8') as f:
            sql_content = f.read()
        
        # Convertir
        print("Convirtiendo de MySQL a PostgreSQL...")
        postgresql_content = convert_mysql_to_postgresql(sql_content)
        
        # Escribir archivo PostgreSQL
        print(f"Escribiendo {output_file}...")
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write(postgresql_content)
        
        print("✅ Conversión completada!")
        print(f"Archivo guardado en: {output_file}")
        
    except FileNotFoundError:
        print(f"❌ Error: Archivo '{input_file}' no encontrado")
        sys.exit(1)
    except Exception as e:
        print(f"❌ Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
