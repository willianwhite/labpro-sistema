<?php
// Verificar informações do servidor e configurações
header('Content-Type: text/plain');

echo "=== INFORMAÇÕES DO SERVIDOR ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Remote Addr: " . $_SERVER['REMOTE_ADDR'] . "\n";
echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n";

echo "\n=== LIMITES DO PHP ===\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "s\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";

echo "\n=== PERMISSÕES ===\n";
echo "File Permissions:\n";
echo "clientes_final.php: " . (file_exists('clientes_final.php') ? substr(sprintf('%o', fileperms('clientes_final.php')), -4) : 'NÃO EXISTE') . "\n";
echo "test_post.php: " . (file_exists('test_post.php') ? substr(sprintf('%o', fileperms('test_post.php')), -4) : 'NÃO EXISTE') . "\n";

echo "\n=== EXTENSÕES PHP ===\n";
$extensions = get_loaded_extensions();
$required_extensions = ['mysqli', 'json', 'mbstring'];
foreach ($required_extensions as $ext) {
    echo $ext . ": " . (in_array($ext, $extensions) ? 'INSTALADO' : 'NÃO INSTALADO') . "\n";
}

echo "\n=== TESTE DE ESCRITA ===\n";
$test_file = $_SERVER['DOCUMENT_ROOT'] . '/test_write_' . time() . '.txt';
if (file_put_contents($test_file, 'Teste de escrita ' . date('Y-m-d H:i:s'))) {
    echo "Escrita OK: " . $test_file . "\n";
    unlink($test_file);
} else {
    echo "Escrita FALHOU\n";
}

echo "\n=== VARIÁVEIS DE AMBIENTE ===\n";
echo "Error Reporting: " . ini_get('error_reporting') . "\n";
echo "Display Errors: " . ini_get('display_errors') . "\n";
echo "Log Errors: " . ini_get('log_errors') . "\n";
echo "Error Log: " . ini_get('error_log') . "\n";
?>
