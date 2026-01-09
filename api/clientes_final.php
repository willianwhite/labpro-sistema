<?php
// API de Clientes LabPro - Versão MySQL (CORRIGIDA)
// Limpar qualquer output anterior
if (ob_get_level()) {
    ob_clean();
}

// Definir headers APENAS uma vez
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
    // Debug: Logar dados recebidos
    error_log("POST request received: " . file_get_contents('php://input'));
    
    $json_input = file_get_contents('php://input');
    if (empty($json_input)) {
        error_log("JSON input is empty");
        echo json_encode(['success' => false, 'message' => 'JSON vazio recebido']);
        exit;
    }
    
    $data = json_decode($json_input, true);
    
    // Debug: Verificar se JSON foi decodificado
    if ($data === null) {
        error_log("JSON decode error: " . json_last_error_msg());
        echo json_encode(['success' => false, 'message' => 'JSON inválido', 'debug' => $json_input]);
        exit;
    }
    
    error_log("Dados decodificados: " . print_r($data, true));
    
    if (!$data || empty($data['nome'])) {
        echo json_encode(['success' => false, 'message' => 'Nome obrigatório']);
        exit;
    }
    
    // Verificar estrutura da tabela
    error_log("Verificando estrutura da tabela clientes...");
    $table_check = $conn->query("DESCRIBE clientes");
    if ($table_check) {
        while ($row = $table_check->fetch_assoc()) {
            error_log("Campo: " . $row['Field'] . " - Tipo: " . $row['Type']);
        }
    }
    
    $sql = "INSERT INTO clientes (tipo_cliente, nome, whatsapp, cpf, cnpj, email, celular, ativo, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    error_log("SQL: " . $sql);
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssssi", 
            $data['tipoCliente'], 
            $data['nome'], 
            $data['whatsapp'], 
            $data['cpf'] ?? '', 
            $data['cnpj'] ?? '', 
            $data['email'] ?? '',
            $data['celular'] ?? '',
            1,
            NOW()
        );
        
        error_log("Parâmetros bindados com sucesso");
        
        if ($stmt->execute()) {
            error_log("Cliente inserido com sucesso");
            $response = ['success' => true, 'message' => 'Cliente criado com sucesso'];
            echo json_encode($response);
            exit;
        } else {
            error_log("Erro ao executar stmt: " . $stmt->error);
            $response = ['success' => false, 'message' => 'Erro ao criar cliente', 'error' => $stmt->error];
            echo json_encode($response);
            exit;
        }
        $stmt->close();
    } else {
        error_log("Erro na preparação da query: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Erro na query', 'error' => $conn->error]);
    }
    exit;
}

// PUT - Atualizar cliente
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID do cliente obrigatório']);
        exit;
    }
    
    // Verificar se o cliente existe antes de atualizar
    $checkSql = "SELECT id FROM clientes WHERE id = ? AND ativo = 1";
    $checkStmt = $conn->prepare($checkSql);
    
    if ($checkStmt) {
        $checkStmt->bind_param("s", $data['id']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $clienteExists = $checkResult->num_rows > 0;
        $checkStmt->close();
        
        if (!$clienteExists) {
            echo json_encode(['success' => false, 'message' => 'Cliente não encontrado ou já foi excluído']);
            exit;
        }
    }
    
    $sql = "UPDATE clientes SET whatsapp = ?, email = ? WHERE id = ? AND ativo = 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sss", 
            $data['whatsapp'], 
            $data['email'], 
            $data['id']
        );
        
        if ($stmt->execute()) {
            $affectedRows = $stmt->affected_rows;
            if ($affectedRows > 0) {
                echo json_encode(['success' => true, 'message' => 'Cliente atualizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nenhum dado foi alterado']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar cliente', 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro na query', 'error' => $conn->error]);
    }
    exit;
}

// DELETE - Excluir cliente
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID do cliente obrigatório']);
        exit;
    }
    
    // Verificar se o cliente existe antes de excluir
    $deleteCheckSql = "SELECT id FROM clientes WHERE id = ? AND ativo = 1";
    $deleteCheckStmt = $conn->prepare($deleteCheckSql);
    
    if ($deleteCheckStmt) {
        $deleteCheckStmt->bind_param("s", $data['id']);
        $deleteCheckStmt->execute();
        $deleteCheckResult = $deleteCheckStmt->get_result();
        $clienteExists = $deleteCheckResult->num_rows > 0;
        $deleteCheckStmt->close();
        
        if (!$clienteExists) {
            echo json_encode(['success' => false, 'message' => 'Cliente não encontrado ou já foi excluído']);
            exit;
        }
    }
    
    // Soft delete - marcar como inativo em vez de apagar
    $sql = "UPDATE clientes SET ativo = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $data['id']);
        
        if ($stmt->execute()) {
            $affectedRows = $stmt->affected_rows;
            if ($affectedRows > 0) {
                echo json_encode(['success' => true, 'message' => 'Cliente excluído com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nenhum cliente foi excluído']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir cliente', 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro na query', 'error' => $conn->error]);
    }
    exit;
}

// GET - Listar clientes ou buscar por ID
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Verificar se há parâmetro ID
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
