<?php
// Script para visualizar logs de erro do PHP
header('Content-Type: text/plain');

// Caminho do log de erros (pode variar conforme o servidor)
$log_file = '/usr/local/cpanel/whm-server/root/usr/local/cpanel/logs/error_log';

if (file_exists($log_file)) {
    echo "=== ÚLTIMOS 50 LINHAS DO LOG DE ERROS ===\n\n";
    $lines = file($log_file);
    $recent_lines = array_slice($lines, -50);
    foreach ($recent_lines as $line) {
        echo $line . "\n";
    }
} else {
    echo "Arquivo de log não encontrado em: " . $log_file . "\n";
    
    // Tentar outros caminhos possíveis
    $possible_paths = [
        '/var/log/php_errors.log',
        '/usr/local/apache/logs/error_log',
        '/home/willi767/logs/error_log',
        $_SERVER['DOCUMENT_ROOT'] . '/../logs/error_log',
        $_SERVER['DOCUMENT_ROOT'] . '/error_log'
    ];
    
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            echo "Log encontrado em: " . $path . "\n";
            $lines = file($path);
            $recent_lines = array_slice($lines, -20);
            foreach ($recent_lines as $line) {
                echo $line . "\n";
            }
            break;
        }
    }
}

// Também mostrar informações do PHP
echo "\n=== INFORMAÇÕES DO PHP ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Error Log: " . ini_get('error_log') . "\n";
echo "Display Errors: " . ini_get('display_errors') . "\n";
echo "Log Errors: " . ini_get('log_errors') . "\n";
?>
