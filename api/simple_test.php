<?php
// Teste ultra simples
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Testar diferentes combinações
$configs = [
    [
        'host' => 'localhost',
        'user' => 'willi767_labpro_user',
        'pass' => 'SuaSenhaAqui123',
        'db' => 'willi767_labpro'
    ],
    [
        'host' => 'localhost',
        'user' => 'root',
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
                'config' => $config,
                'success' => false,
                'error' => $conn->connect_error
            ];
        } else {
            $results[] = [
                'test' => $i + 1,
                'config' => $config,
                'success' => true,
                'message' => 'Conectado!'
            ];
        }
        $conn->close();
        
    } catch (Exception $e) {
        $results[] = [
            'test' => $i + 1,
            'config' => $config,
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

echo json_encode([
    'tests' => $results,
    'php_version' => PHP_VERSION,
    'mysql_extension' => extension_loaded('mysqli') ? 'YES' : 'NO'
]);
?>
