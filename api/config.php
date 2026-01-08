<?php
// Configurações do Banco de Dados MySQL
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'willi767_labpro');
define('DB_USER', 'willi767_labpro_user');
define('DB_PASSWORD', 'Escola123!');

// Configurações da API
define('API_VERSION', '1.0.0');
define('API_ENV', 'production');

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Método OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Conexão com MySQL
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Verificar conexão
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão com o banco de dados',
        'error' => $conn->connect_error
    ]);
    exit();
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Função para resposta JSON
function response($success, $message, $data = null, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'meta' => [
            'timestamp' => date('c'),
            'version' => API_VERSION,
            'environment' => API_ENV
        ]
    ]);
    exit();
}

// Função para logging
function log_message($message) {
    $log_file = __DIR__ . '/logs/api.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Log de requisição
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
log_message("Request: $request_method $request_uri");
?>
