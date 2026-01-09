<?php
// Teste simples para verificar se o PHP está funcionando
header('Content-Type: application/json');

try {
    // Testar conexão
    $conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');
    
    if ($conn->connect_error) {
        echo json_encode([
            'success' => false, 
            'message' => 'Conexão falhou', 
            'error' => $conn->connect_error
        ]);
        exit;
    }
    
    // Testar query simples
    $result = $conn->query("SELECT COUNT(*) as total FROM clientes");
    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'message' => 'Conexão OK',
            'total_clientes' => $row['total']
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
