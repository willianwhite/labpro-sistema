<?php
// Conexão MySQL
$conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');
if ($conn->connect_error) {
    die(json_encode(['error' => 'Conexão falhou']));
}

// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// POST - Criar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO clientes (tipo_cliente, nome, whatsapp, cpf, cnpj, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $data['tipoCliente'], $data['nome'], $data['whatsapp'], 
        $data['cpf'] ?? null, $data['cnpj'] ?? null, $data['email'] ?? null
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Cliente criado']);
    exit;
}

// GET - Listar clientes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = $conn->query("SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC");
    $clientes = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $clientes]);
    exit;
}

echo json_encode(['error' => 'Método não permitido']);
?>