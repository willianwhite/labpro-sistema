<?php
// Versão simplificada do GET
header('Content-Type: application/json');

try {
    // Conexão simples
    $conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');
    
    if ($conn->connect_error) {
        echo json_encode([
            'success' => false,
            'message' => 'Conexão falhou',
            'error' => $conn->connect_error
        ]);
        exit;
    }
    
    // Query simples
    $result = $conn->query("SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC");
    
    if ($result) {
        $clientes = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'success' => true,
            'data' => $clientes
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro na query',
            'error' => $conn->error
        ]);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Exceção capturada',
        'error' => $e->getMessage()
    ]);
}
?>
