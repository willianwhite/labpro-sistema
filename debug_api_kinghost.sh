#!/bin/bash

# Script para debug da API no KingHost
echo "=== DEBUG DA API NO KINGHOST ==="
echo "Iniciado em: $(date)"
echo ""

# Configurações
API_URL="http://labpro.web1f19.kinghost.net/api/clientes_final.php"

echo "=== 1. TESTANDO API VIA CURL ==="
echo "URL: $API_URL"
echo ""

# Testar método GET
echo "Testando método GET..."
curl -X GET "$API_URL" \
  -H "Content-Type: application/json" \
  -v \
  2>&1

echo ""
echo "=== 2. VERIFICANDO ARQUIVO DA API ==="
echo "Verificando se o arquivo existe no servidor..."
ls -la /home/labpro/www/api/clientes_final.php

echo ""
echo "=== 3. VERIFICANDO LOGS DE ERRO ==="
echo "Procurando logs de erro..."
find /home/labpro -name "*.log" -type f 2>/dev/null | head -10

echo ""
echo "=== 4. TESTANDO PHP DIRETAMENTE ==="
echo "Testando execução do PHP..."
php -r "
echo '<?php
// Configurações
define('DB_HOST', 'mysql.labpro.kinghost.net');
define('DB_USER', 'labpro');
define('DB_PASS', 'Escola123');
define('DB_NAME', 'labpro');

// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Conexão
    \$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (\$conn->connect_error) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro de conexão',
            'error' => \$conn->connect_error
        ]);
        exit;
    }
    
    // Query GET
    if (\$_SERVER['REQUEST_METHOD'] == 'GET') {
        \$sql = \"SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC\";
        \$result = \$conn->query(\$sql);
        
        if (\$result) {
            \$clientes = \$result->fetch_all(MYSQLI_ASSOC);
            echo json_encode([
                'success' => true,
                'data' => \$clientes,
                'count' => count(\$clientes)
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erro na query',
                'error' => \$conn->error
            ]);
        }
    }
    
    \$conn->close();
    
} catch (Exception \$e) {
    echo json_encode([
        'success' => false,
        'message' => 'Exceção capturada',
        'error' => \$e->getMessage()
    ]);
}
?>' > /home/labpro/www/api/test_debug.php

echo "Arquivo test_debug.php criado"
echo ""

# Testar o script PHP
echo "Executando test_debug.php..."
curl -X GET "http://labpro.web1f19.kinghost.net/api/test_debug.php" \
  -H "Content-Type: application/json" \
  -v \
  2>&1

echo ""
echo "=== DEBUG CONCLUÍDO ==="
echo "Verifique os resultados acima"
echo "Finalizado em: $(date)"
