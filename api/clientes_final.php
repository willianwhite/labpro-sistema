<?php
// API de Clientes LabPro - Versão com Configuração Centralizada
// Incluir carregador de configuração
require_once __DIR__ . '/config_loader.php';

// Verificar se a configuração foi carregada
if (!defined('DB_HOST')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro: Configuração não encontrada',
        'error' => 'Arquivo config.php não localizado ou inválido'
    ]);
    exit;
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
    exit;
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
    exit;
}

// Log de requisição (apenas em debug)
if (DEBUG_MODE) {
    error_log("Request: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);
}

// GET - Listar clientes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Buscar cliente específico por ID
        $sql = "SELECT * FROM clientes WHERE id = ? AND ativo = 1";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result) {
                $cliente = $result->fetch_assoc();
                if ($cliente) {
                    echo json_encode(['success' => true, 'clientes' => [$cliente]]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Cliente não encontrado']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro na consulta', 'error' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro na query', 'error' => $conn->error]);
        }
    } else {
        // Listar todos os clientes
        try {
            $result = $conn->query("SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC");
            
            if ($result) {
                $clientes = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode(['success' => true, 'data' => $clientes]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao listar clientes', 'error' => $conn->error]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Exceção ao listar clientes', 'error' => $e->getMessage()]);
        }
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Método não permitido']);
$conn->close();
?>
