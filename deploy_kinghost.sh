#!/bin/bash

# Script de Deploy para KingHost
echo "=== DEPLOY AUTOMÁTICO KINGHOST ==="
echo "Iniciado em: $(date)"
echo ""

# Configurações do servidor
SERVER_IP="web143.kinghost.net"
SERVER_USER="labpro"
SERVER_PASS="Escola123"
SERVER_PATH="www"
LOCAL_PATH="/c/Users/MeuPC/Desktop/labpro-deploy"

echo "Servidor: $SERVER_IP"
echo "Usuário: $SERVER_USER"
echo "Caminho remoto: $SERVER_PATH"
echo "Caminho local: $LOCAL_PATH"
echo ""

# Função para executar comandos SSH
execute_ssh() {
    echo "Executando: $1"
    echo "$1" | sshpass -p "$SERVER_PASS" ssh -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP"
}

# 1. Verificar conexão
echo "=== 1. TESTANDO CONEXÃO ==="
execute_ssh "echo 'Conexão OK!' && pwd && ls -la"

# 2. Verificar estrutura atual
echo ""
echo "=== 2. VERIFICANDO ESTRUTURA ATUAL ==="
execute_ssh "ls -la $SERVER_PATH"

# 3. Criar backup do que existe
echo ""
echo "=== 3. CRIANDO BACKUP ==="
execute_ssh "if [ -d '$SERVER_PATH' ]; then tar -czf /home/labpro/backup_$(date +%Y%m%d_%H%M%S).tar.gz -C /home/labpro public_html; else echo 'Diretório não existe'; fi"

# 4. Remover conteúdo antigo
echo ""
echo "=== 4. LIMPANDO DIRETÓRIO ==="
execute_ssh "rm -rf $SERVER_PATH/*"

# 5. Criar estrutura básica
echo ""
echo "=== 5. CRIANDO ESTRUTURA ==="
execute_ssh "mkdir -p $SERVER_PATH/api"

# 6. Verificar PHP
echo ""
echo "=== 6. VERIFICANDO PHP ==="
execute_ssh "php -v && echo 'PHP OK' || echo 'PHP ERROR'"

# 7. Verificar MySQL
echo ""
echo "=== 7. VERIFICANDO MYSQL ==="
execute_ssh "which mysql && mysql --version || echo 'MySQL não encontrado'"

echo ""
echo "=== DEPLOY PREPARADO ==="
echo "Pronto para transferir arquivos!"
echo ""
