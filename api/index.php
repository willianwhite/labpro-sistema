<?php
require_once 'config.php';

// GET /api/health - Health check
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $uptime = 0;
    if (function_exists('sys_getloadavg')) {
        $uptime = sys_getloadavg()[0];
    }
    
    $health_data = [
        'status' => 'OK',
        'timestamp' => date('c'),
        'uptime' => $uptime,
        'environment' => API_ENV,
        'version' => API_VERSION,
        'database' => [
            'connected' => !$conn->connect_error,
            'host' => DB_HOST,
            'database' => DB_NAME
        ],
        'server' => [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'request_uri' => $_SERVER['REQUEST_URI']
        ]
    ];
    
    response(true, 'API funcionando perfeitamente', $health_data, 200);
}

// GET /api - Informações da API
if ($_SERVER['REQUEST_METHOD'] == 'GET' && empty($_GET['action'])) {
    $api_info = [
        'message' => '🦷 LabPro API - Backend PHP/MySQL',
        'version' => API_VERSION,
        'endpoints' => [
            'clientes' => '/api/clientes.php',
            'health' => '/api/index.php',
            'documentation' => 'Endpoints disponíveis via GET'
        ],
        'status' => 'running',
        'methods' => ['GET', 'POST', 'PUT', 'DELETE']
    ];
    
    response(true, 'API LabPro funcionando', $api_info, 200);
}

// Se não for nenhuma rota
response(false, 'Endpoint não encontrado', [
    'path' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'available_endpoints' => [
        'GET /api' => 'Informações da API',
        'GET /api/index.php' => 'Health check',
        'GET /api/clientes.php' => 'Listar clientes',
        'POST /api/clientes.php' => 'Criar cliente',
        'GET /api/clientes.php?id=X' => 'Buscar cliente',
        'PUT /api/clientes.php?id=X' => 'Atualizar cliente',
        'DELETE /api/clientes.php?id=X' => 'Excluir cliente'
    ]
], 404);
?>
