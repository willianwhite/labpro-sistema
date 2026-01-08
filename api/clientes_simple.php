<?php
// API simples de clientes sem banco (fallback)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Arquivo JSON para armazenar clientes
$dataFile = __DIR__ . '/clientes_data.json';

// POST - Criar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['nome'])) {
        echo json_encode(['success' => false, 'message' => 'Nome obrigatório']);
        exit;
    }
    
    // Ler clientes existentes
    $clientes = [];
    if (file_exists($dataFile)) {
        $clientes = json_decode(file_get_contents($dataFile), true) ?: [];
    }
    
    // Adicionar novo cliente
    $novoCliente = [
        'id' => uniqid(),
        'nome' => $input['nome'],
        'tipoCliente' => $input['tipoCliente'] ?? 'dentista',
        'whatsapp' => $input['whatsapp'] ?? '',
        'cpf' => $input['cpf'] ?? '',
        'cnpj' => $input['cnpj'] ?? '',
        'email' => $input['email'] ?? '',
        'data_cadastro' => date('Y-m-d H:i:s')
    ];
    
    $clientes[] = $novoCliente;
    
    // Salvar
    file_put_contents($dataFile, json_encode($clientes, JSON_PRETTY_PRINT));
    
    echo json_encode(['success' => true, 'message' => 'Cliente criado', 'data' => $novoCliente]);
    exit;
}

// GET - Listar clientes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $clientes = [];
    if (file_exists($dataFile)) {
        $clientes = json_decode(file_get_contents($dataFile), true) ?: [];
    }
    
    echo json_encode(['success' => true, 'data' => $clientes]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Método não permitido']);
?>
