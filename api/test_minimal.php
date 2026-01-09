<?php
// Teste mínimo absoluto
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    echo "=== INÍCIO DO TESTE ===\n";
    
    // Apenas testar se o PHP funciona
    $test_data = [
        'success' => true,
        'message' => 'PHP está funcionando',
        'timestamp' => date('Y-m-d H:i:s'),
        'server_info' => [
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown'
        ]
    ];
    
    echo json_encode($test_data);
    
} catch (Throwable $e) {
    echo "=== ERRO CAPTURADO ===\n";
    echo "Tipo: " . get_class($e) . "\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    
    echo json_encode([
        'success' => false,
        'message' => 'Erro capturado',
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
