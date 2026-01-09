#!/bin/bash

# Script para sincronizar arquivos com KingHost
echo "=== SINCRONIZANDO ARQUIVOS ==="
echo "Iniciado em: $(date)"
echo ""

# Configurações
SERVER_IP="web143.kinghost.net"
SERVER_USER="labpro"
SERVER_PASS="Escola123"
SERVER_PATH="/home/labpro/public_html"
LOCAL_PATH="/c/Users/MeuPC/Desktop/labpro-deploy"

# Arquivos para sincronizar
FILES=(
    "cadastros.html"
    "producao.html"
    "api/clientes_final.php"
    "api/config.php"
    "api/.htaccess"
    "api/htaccess"
    "api/index.php"
    "shared.js"
    "images/"
    "favicon.ico"
    "index.html"
)

echo "Arquivos para sincronizar:"
for file in "${FILES[@]}"; do
    echo "  - $file"
done
echo ""

# Função para upload via SCP
upload_file() {
    local_file="$1"
    remote_file="$2"
    
    echo "Upload: $local_file -> $remote_file"
    
    # Criar diretório remoto se não existir
    remote_dir=$(dirname "$remote_file")
    echo "$SERVER_PASS" | ssh -o StrictHostKeyChecking=no "$SERVER_USER@$SERVER_IP" "mkdir -p $remote_dir"
    
    # Upload do arquivo
    echo "$SERVER_PASS" | scp -o StrictHostKeyChecking=no "$local_file" "$SERVER_USER@$SERVER_IP:$remote_file"
}

# Upload dos arquivos
for file in "${FILES[@]}"; do
    if [ -f "$LOCAL_PATH/$file" ]; then
        upload_file "$LOCAL_PATH/$file" "$SERVER_PATH/$file"
    elif [ -d "$LOCAL_PATH/$file" ]; then
        echo "Upload directory: $file"
        echo "$SERVER_PASS" | scp -r -o StrictHostKeyChecking=no "$LOCAL_PATH/$file" "$SERVER_USER@$SERVER_IP:$SERVER_PATH/"
    else
        echo "Arquivo não encontrado: $file"
    fi
done

echo ""
echo "=== SINCRONIZAÇÃO CONCLUÍDA ==="
echo "Finalizado em: $(date)"
