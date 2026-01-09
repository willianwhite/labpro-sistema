<?php
// Script para corrigir permissões dos arquivos
header('Content-Type: text/plain');

echo "=== CORRIGINDO PERMISSÕES ===\n";

$files = [
    'clientes_final.php',
    'test_post.php',
    'server_info.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $current_perms = fileperms($file);
        echo "Arquivo: $file\n";
        echo "Permissões atuais: " . substr(sprintf('%o', $current_perms), -4) . "\n";
        
        // Tentar mudar para 0644 (owner read/write, group/others read)
        if (chmod($file, 0644)) {
            echo "Permissões corrigidas para 0644\n";
        } else {
            echo "FALHA ao corrigir permissões\n";
        }
        
        // Tentar mudar owner para o web server
        $server_user = posix_getpwuid(posix_getuid())['name'];
        echo "Usuário atual: $server_user\n";
        
        echo "---\n";
    } else {
        echo "Arquivo não encontrado: $file\n";
    }
}

echo "\n=== VERIFICANDO DONO DOS ARQUIVOS ===\n";
foreach ($files as $file) {
    if (file_exists($file)) {
        $stat = stat($file);
        $owner = posix_getpwuid($stat['uid'])['name'];
        $group = posix_getgrgid($stat['gid'])['name'];
        echo "$file -> Owner: $owner, Group: $group\n";
    }
}

echo "\n=== TESTE FINAL ===\n";
$test_file = 'test_final_' . time() . '.txt';
if (file_put_contents($test_file, 'Teste final ' . date('Y-m-d H:i:s'))) {
    echo "Teste final OK: $test_file\n";
    unlink($test_file);
} else {
    echo "Teste final FALHOU\n";
}
?>
