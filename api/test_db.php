<?php
// Teste de conexão com banco de dados
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Configurações (mesmas do config.php)
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'willi767_labpro');
define('DB_USER', 'willi767_labpro_user');
define('DB_PASSWORD', 'SuaSenhaAqui123');

try {
    // Tentar conexão
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        echo json_encode([
            'success' => false,
            'error' => 'Conexão falhou: ' . $conn->connect_error,
            'config' => [
                'host' => DB_HOST,
                'port' => DB_PORT,
                'database' => DB_NAME,
                'user' => DB_USER,
                'password' => '***'
            ]
        ]);
    } else {
        // Testar query simples
        $result = $conn->query("SHOW TABLES");
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Conexão bem-sucedida!',
            'database' => DB_NAME,
            'tables' => $tables,
            'table_count' => count($tables)
        ]);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erro: ' . $e->getMessage()
    ]);
}
?>
