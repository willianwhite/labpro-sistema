<?php
// Script para corrigir TODAS as permissões do sistema
header('Content-Type: text/plain');

echo "=== RECUPERAÇÃO DE PERMISSÕES ===\n";
echo "Iniciado em: " . date('Y-m-d H:i:s') . "\n\n";

// Diretório base
$base_dir = __DIR__ . '/..';
echo "Diretório base: " . $base_dir . "\n\n";

// Função para corrigir permissões recursivamente
function fixPermissions($dir) {
    echo "Processando: " . $dir . "\n";
    
    if (!is_dir($dir)) {
        echo "  ERRO: Diretório não existe\n";
        return false;
    }
    
    // Corrigir permissões do diretório
    if (chmod($dir, 0755)) {
        echo "  ✓ Diretório corrigido para 755\n";
    } else {
        echo "  ✗ Falha ao corrigir diretório\n";
    }
    
    // Processar arquivos no diretório
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        $path = $dir . '/' . $item;
        
        if (is_dir($path)) {
            fixPermissions($path);
        } elseif (is_file($path)) {
            // Arquivos PHP executáveis
            if (pathinfo($path, PATHINFO_EXTENSION) == 'php') {
                if (chmod($path, 0644)) {
                    echo "  ✓ Arquivo PHP: " . basename($path) . " -> 644\n";
                } else {
                    echo "  ✗ Falha no arquivo PHP: " . basename($path) . "\n";
                }
            }
            // Outros arquivos
            else {
                if (chmod($path, 0644)) {
                    echo "  ✓ Arquivo: " . basename($path) . " -> 644\n";
                } else {
                    echo "  ✗ Falha no arquivo: " . basename($path) . "\n";
                }
            }
        }
    }
    
    return true;
}

// Corrigir permissões do diretório base
echo "=== CORRIGINDO PERMISSÕES DO DIRETÓRIO BASE ===\n";
fixPermissions($base_dir);

echo "\n=== VERIFICAÇÃO FINAL ===\n";

// Verificar arquivos críticos
$critical_files = [
    'cadastros.html',
    'producao.html',
    'api/clientes_final.php',
    'api/test_post.php',
    'api/server_info.php'
];

foreach ($critical_files as $file) {
    $full_path = $base_dir . '/' . $file;
    if (file_exists($full_path)) {
        $perms = fileperms($full_path);
        $octal = substr(sprintf('%o', $perms), -4);
        echo "  $file: $octal\n";
    } else {
        echo "  $file: NÃO ENCONTRADO\n";
    }
}

echo "\n=== TESTE DE ESCRITA ===\n";
$test_file = $base_dir . '/permission_test_' . time() . '.txt';
if (file_put_contents($test_file, 'Teste de permissões ' . date('Y-m-d H:i:s'))) {
    echo "  ✓ Escrita OK: " . basename($test_file) . "\n";
    unlink($test_file);
} else {
    echo "  ✗ Escrita FALHOU\n";
}

echo "\n=== CONCLUÍDO ===\n";
echo "Finalizado em: " . date('Y-m-d H:i:s') . "\n";
echo "Se você seeing this message, o PHP está funcionando!\n";
?>
