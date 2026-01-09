<?php
// API de Clientes LabPro - Versão KingHost (CONFIGURAÇÃO MANUAL)
require_once __DIR__ . '/config_loader.php';

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

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Conexão MySQL - usando configuração centralizada
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
$conn->set_charset('utf8mb4');

// Função para resposta JSON
function response($success, $message, $data = null, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Log de requisição (apenas em debug)
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_log("Request: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);
}

// GET - Listar clientes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        // Buscar cliente específico
        $id = $_GET['id'];
        $sql = "SELECT * FROM clientes WHERE id = ? AND ativo = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            $cliente = $result->fetch_assoc();
            response(true, 'Cliente encontrado', array($cliente));
        } else {
            response(false, 'Cliente não encontrado');
        }
        $stmt->close();
    } else {
        // Listar todos os clientes
        $sql = "SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC";
        $result = $conn->query($sql);
        
        if ($result) {
            $clientes = $result->fetch_all(MYSQLI_ASSOC);
            response(true, 'Clientes listados com sucesso', $clientes);
        } else {
            response(false, 'Erro ao listar clientes', $conn->error);
        }
    }
}

// POST - Criar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['nome'])) {
        response(false, 'Nome do cliente é obrigatório');
    }
    
    $sql = "INSERT INTO clientes (tipo_cliente, nome, whatsapp, cpf, cnpj, email, celular, ativo, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", 
        $input['tipo_cliente'] ?? 'PF',
        $input['nome'],
        $input['whatsapp'] ?? '',
        $input['cpf'] ?? '',
        $input['cnpj'] ?? '',
        $input['email'] ?? '',
        $input['celular'] ?? ''
    );
    
    if ($stmt->execute()) {
        response(true, 'Cliente criado com sucesso', array('id' => $conn->insert_id));
    } else {
        response(false, 'Erro ao criar cliente', $stmt->error);
    }
    $stmt->close();
}

// PUT - Atualizar cliente
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['id']) || empty($input['nome'])) {
        response(false, 'ID e nome são obrigatórios');
    }
    
    $sql = "UPDATE clientes SET nome = ?, whatsapp = ?, cpf = ?, cnpj = ?, email = ?, celular = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", 
        $input['nome'],
        $input['whatsapp'] ?? '',
        $input['cpf'] ?? '',
        $input['cnpj'] ?? '',
        $input['email'] ?? '',
        $input['celular'] ?? '',
        $input['id']
    );
    
    if ($stmt->execute()) {
        response(true, 'Cliente atualizado com sucesso');
    } else {
        response(false, 'Erro ao atualizar cliente', $stmt->error);
    }
    $stmt->close();
}

// DELETE - Excluir cliente (soft delete)
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || empty($input['id'])) {
        response(false, 'ID do cliente é obrigatório');
    }
    
    // Verificar se cliente existe
    $check_sql = "SELECT id FROM clientes WHERE id = ? AND ativo = 1";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $input['id']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $cliente_exists = $check_result->num_rows > 0;
    $check_stmt->close();
    
    if (!$cliente_exists) {
        response(false, 'Cliente não encontrado ou já foi excluído');
    }
    
    // Soft delete - marcar como inativo
    $sql = "UPDATE clientes SET ativo = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input['id']);
    
    if ($stmt->execute()) {
        response(true, 'Cliente excluído com sucesso');
    } else {
        response(false, 'Erro ao excluir cliente', $stmt->error);
    }
    $stmt->close();
}

$conn->close();
?>
