<?php
// API de Login PHP para LabPro
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Responder para OPTIONS (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Credenciais simples (melhorar depois)
$usuarios = [
    'admin' => [
        'senha' => 'admin123',
        'nome' => 'Administrador',
        'tipo' => 'admin'
    ],
    'dentista' => [
        'senha' => 'dentista123',
        'nome' => 'Dentista Teste',
        'tipo' => 'dentista'
    ]
];

// Receber dados
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['username']) || empty($input['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário e senha obrigatórios'
    ]);
    exit;
}

$username = $input['username'];
$password = $input['password'];

// Verificar usuário
if (isset($usuarios[$username]) && $usuarios[$username]['senha'] === $password) {
    $usuario = $usuarios[$username];
    $token = 'token_' . uniqid() . '_' . time();
    
    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso',
        'token' => $token,
        'user' => [
            'username' => $username,
            'nome' => $usuario['nome'],
            'tipo' => $usuario['tipo']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário ou senha incorretos'
    ]);
}
?>
