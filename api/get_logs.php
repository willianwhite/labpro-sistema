<?php
// Script para ler os logs de erro reais
header('Content-Type: text/plain');

$log_file = '/usr/local/apache/logs/error_log';

if (file_exists($log_file)) {
    echo "=== LOGS DE ERRO DO APACHE/PHP ===\n\n";
    
    // Ler as últimas 100 linhas
    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $recent_lines = array_slice($lines, -100);
    
    // Filtrar apenas erros recentes (últimas 24 horas)
    $yesterday = time() - (24 * 60 * 60);
    $filtered_lines = [];
    
    foreach ($recent_lines as $line) {
        if (strpos($line, 'clientes_final.php') !== false) {
            $filtered_lines[] = $line;
        }
    }
    
    if (!empty($filtered_lines)) {
        echo "ERROS RELACIONADOS AO clientes_final.php:\n";
        echo str_repeat("=", 50) . "\n";
        
        foreach ($filtered_lines as $line) {
            echo $line . "\n";
        }
    } else {
        echo "Nenhum erro encontrado para clientes_final.php nas últimas 24 horas.\n";
        echo "\nMostrando últimos 10 erros gerais:\n";
        echo str_repeat("-", 50) . "\n";
        
        $general_errors = array_slice($lines, -10);
        foreach ($general_errors as $line) {
            if (!empty(trim($line))) {
                echo $line . "\n";
            }
        }
    }
} else {
    echo "Arquivo de log não encontrado!\n";
}

echo "\n=== INFORMAÇÕES DO SERVIDOR ===\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server API: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
?>
