<?php
// Testar múltiplos usuários
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$users = [
    ['user' => 'willi767_labpro_user', 'pass' => 'LabPro2024@'],
    ['user' => 'willi767_labpro_user', 'pass' => ''],
    ['user' => 'labpro_user', 'pass' => 'LabPro2024@'],
    ['user' => 'willi767', 'pass' => ''],
    ['user' => 'willi767', 'pass' => 'LabPro2024@'],
    ['user' => 'root', 'pass' => ''],
];

$results = [];

foreach ($users as $i => $user) {
    try {
        $conn = new mysqli('localhost', $user['user'], $user['pass']);
        
        if ($conn->connect_error) {
            $results[] = [
                'test' => $i + 1,
                'user' => $user['user'],
                'pass_length' => strlen($user['pass']),
                'success' => false,
                'error' => $conn->connect_error
            ];
        } else {
            // Testar se pode criar banco
            $dbs = [];
            $result = $conn->query("SHOW DATABASES");
            while ($row = $result->fetch_array()) {
                $dbs[] = $row[0];
            }
            
            $results[] = [
                'test' => $i + 1,
                'user' => $user['user'],
                'pass_length' => strlen($user['pass']),
                'success' => true,
                'databases' => $dbs,
                'message' => 'Conectado!'
            ];
        }
        $conn->close();
        
    } catch (Exception $e) {
        $results[] = [
            'test' => $i + 1,
            'user' => $user['user'],
            'pass_length' => strlen($user['pass']),
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

echo json_encode([
    'results' => $results,
    'message' => 'Testando diferentes usuários'
]);
?>
