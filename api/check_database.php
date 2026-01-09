<?php
// Verificar conexão e estrutura do banco
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

echo "=== VERIFICAÇÃO DO BANCO DE DADOS ===\n";

try {
    // Conexão
    $conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');
    
    if ($conn->connect_error) {
        echo "ERRO DE CONEXÃO: " . $conn->connect_error . "\n";
        exit;
    }
    
    echo "Conexão OK\n";
    
    // Verificar se a tabela existe
    $table_check = $conn->query("SHOW TABLES LIKE 'clientes'");
    if ($table_check->num_rows == 0) {
        echo "ERRO: Tabela 'clientes' não existe\n";
        
        // Criar tabela
        $create_sql = "CREATE TABLE clientes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tipo_cliente VARCHAR(50) NOT NULL,
            nome VARCHAR(255) NOT NULL,
            whatsapp VARCHAR(20),
            cpf VARCHAR(20),
            cnpj VARCHAR(20),
            email VARCHAR(255),
            celular VARCHAR(20),
            ativo TINYINT(1) DEFAULT 1,
            data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($create_sql)) {
            echo "Tabela 'clientes' criada com sucesso\n";
        } else {
            echo "ERRO ao criar tabela: " . $conn->error . "\n";
        }
    } else {
        echo "Tabela 'clientes' existe\n";
        
        // Verificar estrutura
        $structure = $conn->query("DESCRIBE clientes");
        echo "Estrutura da tabela:\n";
        while ($row = $structure->fetch_assoc()) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
        
        // Contar clientes
        $count = $conn->query("SELECT COUNT(*) as total FROM clientes");
        $total = $count->fetch_assoc()['total'];
        echo "Total de clientes: " . $total . "\n";
        
        // Contar clientes ativos
        $active_count = $conn->query("SELECT COUNT(*) as total FROM clientes WHERE ativo = 1");
        $active_total = $active_count->fetch_assoc()['total'];
        echo "Total de clientes ativos: " . $active_total . "\n";
        
        // Mostrar alguns clientes
        $sample = $conn->query("SELECT id, nome, tipo_cliente, ativo FROM clientes LIMIT 5");
        echo "Amostra de clientes:\n";
        while ($row = $sample->fetch_assoc()) {
            echo "- ID: " . $row['id'] . ", Nome: " . $row['nome'] . ", Tipo: " . $row['tipo_cliente'] . ", Ativo: " . $row['ativo'] . "\n";
        }
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "EXCEÇÃO: " . $e->getMessage() . "\n";
}
?>
