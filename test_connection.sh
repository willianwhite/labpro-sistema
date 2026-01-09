#!/bin/bash

# Script para testar conexão com KingHost
echo "=== TESTE DE CONEXÃO KINGHOST ==="
echo "Iniciado em: $(date)"
echo ""

# Configurações
SERVER_IP="web143.kinghost.net"
SERVER_USER="labpro"
SERVER_PASS="Escola123"

echo "Servidor: $SERVER_IP"
echo "Usuário: $SERVER_USER"
echo "Senha: [CONFIGURADA]"
echo ""

# Testar conexão SSH
echo "=== 1. TESTANDO CONEXÃO SSH ==="
echo "$SERVER_PASS" | ssh -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP" "echo 'Conexão SSH OK!' && pwd && ls -la"

echo ""
echo "=== 2. VERIFICANDO PASTA WWW ==="
echo "$SERVER_PASS" | ssh -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP" "cd www && pwd && ls -la"

echo ""
echo "=== 3. VERIFICANDO PHP ==="
echo "$SERVER_PASS" | ssh -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP" "php -v"

echo ""
echo "=== TESTE CONCLUÍDO ==="
