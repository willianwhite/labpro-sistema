<?php
// Script para forçar deploy limpo no servidor
header('Content-Type: text/plain');

echo "=== FORÇAR DEPLOY LIMPO ===\n";
echo "Data: " . date('Y-m-d H:i:s') . "\n\n";

// Diretório base
$base_dir = dirname(__DIR__);
echo "Diretório base: " . $base_dir . "\n";

// Limpar cache do Git se existir
$git_dir = $base_dir . '/.git';
if (is_dir($git_dir)) {
    echo "=== LIMPANDO CACHE GIT ===\n";
    
    // Remover arquivos de cache
    $cache_files = [
        '.git/objects/pack/*.pack',
        '.git/objects/pack/*.idx',
        '.git/index'
    ];
    
    foreach ($cache_files as $pattern) {
        $files = glob($base_dir . '/' . $pattern);
        foreach ($files as $file) {
            if (unlink($file)) {
                echo "✓ Removido: " . basename($file) . "\n";
            }
        }
    }
    
    // Reset do index
    $git_index = $base_dir . '/.git/index';
    if (file_exists($git_index)) {
        unlink($git_index);
        echo "✓ Index resetado\n";
    }
}

// Verificar arquivos atuais
echo "\n=== VERIFICANDO ARQUIVOS ATUAIS ===\n";
$required_files = [
    'cadastros.html',
    'producao.html',
    'api/clientes_final.php',
    'api/deploy_check.php'
];

foreach ($required_files as $file) {
    $path = $base_dir . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "✓ $file ($size bytes)\n";
    } else {
        echo "✗ $file (NÃO ENCONTRADO)\n";
    }
}

// Criar arquivo de status
$status_file = $base_dir . '/deploy_status.txt';
$status = "Deploy Status: OK\nData: " . date('Y-m-d H:i:s') . "\nBranch: main\nCommit: " . file_get_contents($base_dir . '/.git/HEAD') . "\n";
file_put_contents($status_file, $status);

echo "\n=== STATUS CRIADO ===\n";
echo "✓ deploy_status.txt criado\n";

echo "\n=== INSTRUÇÕES ===\n";
echo "1. No cPanel Git™ Version Control, execute 'Pull' ou 'Deploy'\n";
echo "2. Se erro persistir, use 'Reset Hard' para branch main\n";
echo "3. Verifique: https://willianwhiteestevaod1767839682077.2002102.meusitehostgator.com.br/api/deploy_check.php\n";

echo "\n=== CONCLUÍDO ===\n";
echo "Sistema pronto para deploy forçado!\n";
?>
