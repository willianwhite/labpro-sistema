<?php
// Setup do banco de dados LabPro
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Configurações
define('DB_HOST', 'localhost');
define('DB_USER', 'willi767_labpro_user');
define('DB_PASSWORD', 'LabPro2024!');
define('DB_NAME', 'willi767_labpro');

try {
    // Conectar sem selecionar banco primeiro
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
    
    if ($conn->connect_error) {
        throw new Exception("Conexão falhou: " . $conn->connect_error);
    }
    
    // Criar banco se não existir
    $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->select_db(DB_NAME);
    
    // Criar tabela clientes
    $sql = "CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tipo_cliente VARCHAR(50) DEFAULT 'dentista',
        nome VARCHAR(255) NOT NULL,
        cnpj VARCHAR(20),
        cpf VARCHAR(20),
        email VARCHAR(255),
        celular VARCHAR(20),
        whatsapp VARCHAR(20),
        cep VARCHAR(10),
        rua VARCHAR(255),
        numero VARCHAR(20),
        bairro VARCHAR(255),
        cidade VARCHAR(255),
        uf VARCHAR(2),
        complemento VARCHAR(255),
        tabela_precos VARCHAR(50) DEFAULT 'padrao',
        desconto_geral DECIMAL(5,2) DEFAULT 0.00,
        data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ativo TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'Banco e tabela criados com sucesso!',
            'database' => DB_NAME,
            'table' => 'clientes'
        ]);
    } else {
        throw new Exception("Erro ao criar tabela: " . $conn->error);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'host' => DB_HOST,
            'user' => DB_USER,
            'database' => DB_NAME,
            'php_version' => PHP_VERSION,
            'mysql_extension' => extension_loaded('mysqli') ? 'YES' : 'NO'
        ]
    ]);
}
?>
