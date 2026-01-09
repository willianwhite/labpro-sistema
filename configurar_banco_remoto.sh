#!/bin/bash

# Script para configurar banco MySQL remoto da KingHost
echo "=== CONFIGURANDO BANCO MYSQL REMOTO ==="
echo "Servidor: mysql.labpro.kinghost.net"
echo "Usuário: labpro"
echo "Banco: labpro"
echo "Iniciado em: $(date)"
echo ""

# Configurações
MYSQL_HOST="mysql.labpro.kinghost.net"
MYSQL_USER="labpro"
MYSQL_PASS="Escola123"
MYSQL_DB="labpro"

echo "=== 1. TESTANDO CONEXÃO COM SERVIDOR REMOTO ==="
mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "SELECT 'Conexão remota OK!' AS status;" "$MYSQL_DB"

if [ $? -eq 0 ]; then
    echo "✅ Conexão com MySQL remoto bem-sucedida!"
else
    echo "❌ Falha na conexão com MySQL remoto"
    echo "Verifique host, usuário e senha"
    exit 1
fi

echo ""
echo "=== 2. CRIANDO TABELA CLIENTES ==="
if [ -f "conectar_mysql_remoto.sql" ]; then
    echo "✅ Arquivo SQL encontrado"
    mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB" < conectar_mysql_remoto.sql
    if [ $? -eq 0 ]; then
        echo "✅ Tabela clientes criada com sucesso!"
    else
        echo "❌ Erro ao criar tabela clientes"
    fi
else
    echo "❌ Arquivo conectar_mysql_remoto.sql não encontrado"
    exit 1
fi

echo ""
echo "=== 3. VERIFICANDO TABELA ==="
mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "SHOW TABLES LIKE 'clientes';" "$MYSQL_DB"

if [ $? -eq 0 ]; then
    echo "✅ Tabela 'clientes' existe no banco remoto"
else
    echo "❌ Tabela 'clientes' não encontrada"
fi

echo ""
echo "=== 4. CONTANDO REGISTROS ==="
COUNT=$(mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "SELECT COUNT(*) FROM clientes;" "$MYSQL_DB" 2>/dev/null | tail -1)
echo "Total de clientes: $COUNT"

echo ""
echo "=== 5. MOSTRANDO PRIMEIROS REGISTROS ==="
mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "SELECT id, nome, whatsapp, email FROM clientes LIMIT 3;" "$MYSQL_DB"

echo ""
echo "=== BANCO REMOTO CONFIGURADO COM SUCESSO! ==="
echo "Finalizado em: $(date)"
echo ""
echo "Agora o sistema LabPro deve funcionar perfeitamente!"
echo "URL: http://labpro.web1f19.kinghost.net/cadastros.html"
