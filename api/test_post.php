<?php
// Teste isolado do POST para identificar o erro
header('Content-Type: application/json');

try {
    // Limpar output
    if (ob_get_level()) {
        ob_clean();
    }
    
    // Logar tudo
    error_log("=== TESTE POST INICIADO ===");
    error_log("Raw input: " . file_get_contents('php://input'));
    
    // Testar JSON decode
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);
    
    if ($data === null) {
        error_log("JSON decode falhou: " . json_last_error_msg());
        echo json_encode(['success' => false, 'message' => 'JSON inválido', 'debug' => $json_input]);
        exit;
    }
    
    error_log("Dados recebidos: " . print_r($data, true));
    
    // Testar conexão
    $conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');
    
    if ($conn->connect_error) {
        error_log("Conexão falhou: " . $conn->connect_error);
        echo json_encode(['success' => false, 'message' => 'Conexão falhou', 'error' => $conn->connect_error]);
        exit;
    }
    
    error_log("Conexão OK");
    
    // Query mais simples possível
    $sql = "INSERT INTO clientes (nome, tipo_cliente, ativo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare falhou: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Prepare falhou', 'error' => $conn->error]);
        exit;
    }
    
    error_log("Prepare OK");
    
    // Bind simples
    $bind_result = $stmt->bind_param("sss", 
        $data['nome'] ?? 'Teste',
        $data['tipoCliente'] ?? 'dentista',
        1
    );
    
    if (!$bind_result) {
        error_log("Bind falhou");
        echo json_encode(['success' => false, 'message' => 'Bind falhou']);
        exit;
    }
    
    error_log("Bind OK");
    
    // Executar
    if ($stmt->execute()) {
        error_log("Execute OK - ID: " . $conn->insert_id);
        echo json_encode(['success' => true, 'message' => 'Cliente criado', 'id' => $conn->insert_id]);
    } else {
        error_log("Execute falhou: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Execute falhou', 'error' => $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    error_log("Exceção: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Exceção', 'error' => $e->getMessage()]);
}
?>
