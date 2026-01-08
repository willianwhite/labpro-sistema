<?php
// API de Clientes LabPro - Versão MySQL
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Conexão MySQL
$conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Conexão falhou', 'error' => $conn->connect_error]);
    exit;
}

// POST - Criar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['nome'])) {
        echo json_encode(['success' => false, 'message' => 'Nome obrigatório']);
        exit;
    }
    
    $sql = "INSERT INTO clientes (tipo_cliente, nome, whatsapp, cpf, cnpj, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssssss", 
            $data['tipoCliente'], 
            $data['nome'], 
            $data['whatsapp'], 
            $data['cpf'] ?? '', 
            $data['cnpj'] ?? '', 
            $data['email'] ?? ''
        );
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cliente criado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao criar cliente', 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro na query', 'error' => $conn->error]);
    }
    exit;
}

// GET - Listar clientes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = $conn->query("SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC");
    
    if ($result) {
        $clientes = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'data' => $clientes]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao listar clientes', 'error' => $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Método não permitido']);
$conn->close();
?>