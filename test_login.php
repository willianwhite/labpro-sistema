<?php
// Teste simples da API de login
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'success' => true,
    'message' => 'API de login funcionando!',
    'test' => 'ok',
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
