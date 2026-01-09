#!/bin/bash

# Script para criar banco de dados no KingHost
echo "=== CRIANDO BANCO DE DADOS LABPRO ==="
echo "Iniciado em: $(date)"
echo ""

# Configurações
DB_USER="labpro"
DB_PASS="Escola123!"
DB_NAME="labpro"

echo "Usuário: $DB_USER"
echo "Banco: $DB_NAME"
echo ""

# 1. Verificar se MySQL está funcionando
echo "=== 1. VERIFICANDO MYSQL ==="
if command -v mysql >/dev/null 2>&1; then
    echo "✅ MySQL encontrado"
    mysql --version
else
    echo "❌ MySQL não encontrado"
    exit 1
fi

# 2. Testar conexão
echo ""
echo "=== 2. TESTANDO CONEXÃO ==="
mysql -u "$DB_USER" -p"$DB_PASS" -e "SELECT 'Conexão OK' AS status;" "$DB_NAME" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Conexão com MySQL bem-sucedida"
else
    echo "❌ Falha na conexão com MySQL"
    echo "Verifique usuário e senha"
    exit 1
fi

# 3. Criar tabela usando o arquivo SQL
echo ""
echo "=== 3. CRIANDO TABELA CLIENTES ==="
if [ -f "criar_banco.sql" ]; then
    echo "✅ Arquivo SQL encontrado"
    mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < criar_banco.sql
    if [ $? -eq 0 ]; then
        echo "✅ Tabela clientes criada com sucesso!"
    else
        echo "❌ Erro ao criar tabela clientes"
    fi
else
    echo "❌ Arquivo criar_banco.sql não encontrado"
    exit 1
fi

# 4. Verificar se tabela foi criada
echo ""
echo "=== 4. VERIFICANDO TABELA ==="
mysql -u "$DB_USER" -p"$DB_PASS" -e "SHOW TABLES LIKE 'clientes';" "$DB_NAME" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Tabela 'clientes' existe no banco"
else
    echo "❌ Tabela 'clientes' não encontrada"
fi

# 5. Contar registros
echo ""
echo "=== 5. CONTANDO REGISTROS ==="
COUNT=$(mysql -u "$DB_USER" -p"$DB_PASS" -e "SELECT COUNT(*) FROM clientes;" "$DB_NAME" 2>/dev/null | tail -1)
echo "Total de clientes: $COUNT"

# 6. Mostrar primeiros registros
echo ""
echo "=== 6. PRIMEIROS 3 CLIENTES ==="
mysql -u "$DB_USER" -p"$DB_PASS" -e "SELECT id, nome, whatsapp, email FROM clientes LIMIT 3;" "$DB_NAME" 2>/dev/null

echo ""
echo "=== BANCO CONFIGURADO COM SUCESSO! ==="
echo "Finalizado em: $(date)"
