<?php
// Testar com senha vazia
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$configs = [
    // Teste 1: Usuário com senha vazia
    [
        'host' => 'localhost',
        'user' => 'willi767_labpro_user',
        'pass' => '',
        'db' => 'willi767_labpro'
    ],
    // Teste 2: Usuário com senha LabPro2024!
    [
        'host' => 'localhost',
        'user' => 'willi767_labpro_user',
        'pass' => 'LabPro2024!',
        'db' => 'willi767_labpro'
    ],
    // Teste 3: Tentar com usuário padrão cPanel
    [
        'host' => 'localhost',
        'user' => 'willi767',
        'pass' => '',
        'db' => 'willi767_labpro'
    ]
];

$results = [];

foreach ($configs as $i => $config) {
    try {
        $conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
        
        if ($conn->connect_error) {
            $results[] = [
                'test' => $i + 1,
                'user' => $config['user'],
                'pass_length' => strlen($config['pass']),
                'success' => false,
                'error' => $conn->connect_error
            ];
        } else {
            // Testar se tabela clientes existe
            $tables = [];
            $result = $conn->query("SHOW TABLES");
            while ($row = $result->fetch_array()) {
                $tables[] = $row[0];
            }
            
            $results[] = [
                'test' => $i + 1,
                'user' => $config['user'],
                'pass_length' => strlen($config['pass']),
                'success' => true,
                'tables' => $tables,
                'message' => 'Conectado!'
            ];
        }
        $conn->close();
        
    } catch (Exception $e) {
        $results[] = [
            'test' => $i + 1,
            'user' => $config['user'],
            'pass_length' => strlen($config['pass']),
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

echo json_encode([
    'results' => $results,
    'info' => 'Testando diferentes combinações de usuário/senha'
]);
?>
