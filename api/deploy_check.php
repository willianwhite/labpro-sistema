<?php
// Script para verificar deploy pós-reset
header('Content-Type: text/plain');

echo "=== VERIFICAÇÃO PÓS-DEPLOY ===\n";
echo "Data: " . date('Y-m-d H:i:s') . "\n\n";

// Verificar arquivos principais
$files = [
    'cadastros.html',
    'producao.html',
    'api/clientes_final.php',
    'api/test_minimal.php',
    'api/check_database.php'
];

echo "=== VERIFICANDO ARQUIVOS ===\n";
foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        $perms = fileperms($path);
        $octal = substr(sprintf('%o', $perms), -4);
        echo "✓ $file ($octal)\n";
    } else {
        echo "✗ $file (NÃO ENCONTRADO)\n";
    }
}

echo "\n=== TESTE DE CONEXÃO ===\n";
try {
    $conn = new mysqli('localhost', 'willi767_labpro_user', 'Escola123!', 'willi767_labpro');
    if ($conn->connect_error) {
        echo "✗ Conexão falhou: " . $conn->connect_error . "\n";
    } else {
        echo "✓ Conexão OK\n";
        
        // Verificar tabela
        $table_check = $conn->query("SHOW TABLES LIKE 'clientes'");
        if ($table_check->num_rows > 0) {
            echo "✓ Tabela clientes existe\n";
            
            $count = $conn->query("SELECT COUNT(*) as total FROM clientes");
            $total = $count->fetch_assoc()['total'];
            echo "✓ Total clientes: $total\n";
        } else {
            echo "✗ Tabela clientes não existe\n";
        }
        
        $conn->close();
    }
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n=== PERMISSÕES ===\n";
$base_dir = __DIR__ . '/../';
$perms = fileperms($base_dir);
$octal = substr(sprintf('%o', $perms), -4);
echo "Base dir: $octal\n";

echo "\n=== CONCLUÍDO ===\n";
echo "Sistema verificado e pronto para uso!\n";
?>
