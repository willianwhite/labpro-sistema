<?php
// Configurações para KingHost
define('DB_HOST', 'localhost');
define('DB_USER', 'labpro_db');
define('DB_PASS', 'Escola123!');
define('DB_NAME', 'labpro_sistema');

// Conexão com o banco de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Erro de conexão',
        'error' => $conn->connect_error
    ]));
}

// Headers para API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
?>
