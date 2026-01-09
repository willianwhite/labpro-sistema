<?php
// Carregador de configuração - LabPro
// Usa o config.php centralizado

// Incluir configuração centralizada
require_once __DIR__ . '/../config.php';

// Verificar se a configuração foi carregada
if (!defined('DB_HOST')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro: Configuração não encontrada',
        'error' => 'Arquivo config.php não localizado ou inválido'
    ]);
    exit();
}

// Para debug
if (DEBUG_MODE) {
    error_log("Configuração carregada com sucesso");
    error_log("Ambiente: " . ENVIRONMENT);
    error_log("Banco: " . DB_NAME . "@" . DB_HOST);
}
?>
