#!/bin/bash

# Script para debug da configuração centralizada
echo "=== DEBUG DA CONFIGURAÇÃO ==="
echo "Data: $(date '+%Y-%m-%d %H:%M:%S')"
echo ""

# Criar script PHP de debug
cat > debug_config.php << 'EOF'
<?php
// Script para debug da configuração centralizada - LabPro
echo "=== DEBUG DA CONFIGURAÇÃO ===";
echo "Data: " . date('Y-m-d H:i:s');
echo "";

// Verificar se config.php existe
if (!file_exists(__DIR__ . '/../config.php')) {
    echo "❌ config.php não encontrado\n";
    exit;
}

echo "✅ config.php encontrado\n";

// Tentar incluir configuração
try {
    require_once __DIR__ . '/../config.php';
    echo "✅ require_once funcionou\n";
} catch (Exception $e) {
    echo "❌ Erro no require_once: " . $e->getMessage() . "\n";
    exit;
}

// Verificar constantes definidas
echo "=== CONSTANTES DEFINIDAS ===\n";
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NÃO DEFINIDO') . "\n";
echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NÃO DEFINIDO') . "\n";
echo "DB_USER: " . (defined('DB_USER') ? DB_USER : 'NÃO DEFINIDO') . "\n";
echo "ENVIRONMENT: " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'NÃO DEFINIDO') . "\n";
echo "DEBUG_MODE: " . (defined('DEBUG_MODE') ? (DEBUG_MODE ? 'ATIVADO' : 'DESATIVADO') : 'NÃO DEFINIDO') . "\n";

// Testar conexão com banco
if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_NAME')) {
    echo "=== TESTANDO CONEXÃO ===\n";
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($conn->connect_error) {
            echo "❌ Erro de conexão: " . $conn->connect_error . "\n";
        } else {
            echo "✅ Conexão bem-sucedida\n";
            
            // Testar query simples
            $result = $conn->query("SELECT COUNT(*) as total FROM clientes");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "✅ Query funcionou - Total clientes: " . $row['total'] . "\n";
            } else {
                echo "❌ Erro na query: " . $conn->error . "\n";
            }
            
            $conn->close();
        }
    } catch (Exception $e) {
        echo "❌ Exceção na conexão: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Constantes de conexão não definidas\n";
}

echo "=== DEBUG CONCLUÍDO ===\n";
echo "Finalizado em: " . date('Y-m-d H:i:s') . "\n";
?>
EOF

echo "Script debug_config.php criado com sucesso!"

# Executar o script
echo "Executando debug..."
php debug_config.php
EOF

chmod +x debug_config_fixed.sh
