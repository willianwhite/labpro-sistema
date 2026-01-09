#!/bin/bash

# Script para configurar servidor KingHost
echo "=== CONFIGURAÇÃO DO SERVIDOR KINGHOST ==="
echo "Iniciado em: $(date)"
echo ""

# Configurações
SERVER_IP="web143.kinghost.net"
SERVER_USER="labpro"
SERVER_PASS="Escola123"
SERVER_PATH="www"

# Função para executar comandos SSH
execute_ssh() {
    echo "Executando: $1"
    echo "$1" | ssh -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP"
}

# 1. Configurar permissões
echo "=== 1. CONFIGURANDO PERMISSÕES ==="
execute_ssh "chmod 755 $SERVER_PATH"
execute_ssh "chmod 755 $SERVER_PATH/api"
execute_ssh "find $SERVER_PATH -type f -name '*.php' -exec chmod 644 {} \;"
execute_ssh "find $SERVER_PATH -type f -name '*.html' -exec chmod 644 {} \;"
execute_ssh "find $SERVER_PATH -type f -name '*.js' -exec chmod 644 {} \;"
execute_ssh "find $SERVER_PATH -type f -name '*.css' -exec chmod 644 {} \;"

# 2. Verificar Apache/Nginx
echo ""
echo "=== 2. VERIFICANDO WEB SERVER ==="
execute_ssh "which apache2 && apache2 -v || which nginx && nginx -v || echo 'Web server não identificado'"

# 3. Verificar módulos PHP
echo ""
echo "=== 3. VERIFICANDO MÓDULOS PHP ==="
execute_ssh "php -m | grep -E '(mysqli|json|mbstring)' || echo 'Módulos PHP não encontrados'"

# 4. Configurar .htaccess
echo ""
echo "=== 4. CONFIGURANDO .HTACCESS ==="
execute_ssh "echo 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]' > $SERVER_PATH/.htaccess"

# 5. Testar PHP
echo ""
echo "=== 5. TESTANDO PHP ==="
execute_ssh "echo '<?php echo \"PHP funcionando em: \" . date(\"Y-m-d H:i:s\"); ?>' > $SERVER_PATH/test.php"

# 6. Verificar MySQL
echo ""
echo "=== 6. CONFIGURANDO MYSQL ==="
execute_ssh "which mysql && echo 'MySQL encontrado' || echo 'MySQL não encontrado'"

# 7. Criar banco de dados se não existir
echo ""
echo "=== 7. VERIFICANDO BANCO DE DADOS ==="
execute_ssh "mysql -u root -p'$SERVER_PASS' -e 'CREATE DATABASE IF NOT EXISTS labpro;' 2>/dev/null || echo 'Banco já existe ou erro de conexão'"

echo ""
echo "=== CONFIGURAÇÃO CONCLUÍDA ==="
echo "Acesse: http://web143.kinghost.net/test.php"
echo "Finalizado em: $(date)"
