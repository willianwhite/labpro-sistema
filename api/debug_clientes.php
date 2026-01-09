<?php
// Script de Debug para API de Clientes
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Função de debug
function debug_log($message) {
    $log_file = 'debug_clientes.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

debug_log("Iniciando script de debug");

// Conexão MySQL
debug_log("Tentando conectar ao MySQL");
$conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');

if ($conn->connect_error) {
    debug_log("ERRO: Conexão falhou - " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Conexão falhou', 'error' => $conn->connect_error]);
    exit;
}

debug_log("Conexão bem sucedida");

// Teste de conexão com banco
debug_log("Testando consulta ao banco");
$result = $conn->query("SHOW TABLES");
if ($result) {
    $tables = $result->fetch_all();
    debug_log("Tabelas encontradas: " . json_encode($tables));
} else {
    debug_log("ERRO: Não foi possível listar tabelas - " . $conn->error);
}

// Teste de estrutura da tabela clientes
debug_log("Verificando estrutura da tabela clientes");
$result = $conn->query("DESCRIBE clientes");
if ($result) {
    $columns = $result->fetch_all();
    debug_log("Estrutura da tabela clientes: " . json_encode($columns));
} else {
    debug_log("ERRO: Não foi possível descrever tabela clientes - " . $conn->error);
}

// Teste simples de INSERT
debug_log("Testando INSERT simples");
$test_sql = "INSERT INTO debug_log (mensagem, data_hora) VALUES (?, NOW())";
$stmt = $conn->prepare($test_sql);
if ($stmt) {
    $stmt->bind_param("ss", "Teste de INSERT", "Teste de INSERT");
    if ($stmt->execute()) {
        debug_log("INSERT de teste bem sucedido");
    } else {
        debug_log("ERRO no INSERT de teste - " . $stmt->error);
    }
    $stmt->close();
} else {
    debug_log("ERRO: Não foi possível preparar INSERT de teste - " . $conn->error);
}

$conn->close();
debug_log("Script de debug finalizado");

echo json_encode(['success' => true, 'message' => 'Debug concluído', 'log_file' => 'debug_clientes.log']);
?>
