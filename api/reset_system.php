<?php
// Script para reset completo do sistema
header('Content-Type: text/plain');

echo "=== RESET COMPLETO DO SISTEMA ===\n";
echo "Iniciado em: " . date('Y-m-d H:i:s') . "\n\n";

// Diretório base
$base_dir = dirname(__DIR__);
$public_html = $base_dir . '/public_html';

echo "Diretório base: " . $base_dir . "\n";
echo "Public HTML: " . $public_html . "\n\n";

// Backup se existir
if (file_exists($public_html)) {
    echo "=== BACKUP AUTOMÁTICO ===\n";
    $backup_dir = $base_dir . '/backup_' . date('Y-m-d_H-i-s');
    
    if (rename($public_html, $backup_dir)) {
        echo "✓ Backup criado: " . basename($backup_dir) . "\n";
    } else {
        echo "✗ Falha ao criar backup\n";
    }
}

// Criar nova public_html
echo "\n=== CRIANDO NOVA PUBLIC_HTML ===\n";
if (mkdir($public_html, 0755, true)) {
    echo "✓ Nova public_html criada\n";
    
    // Criar .htaccess básico
    $htaccess = "RewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^(.*)$ index.php [QSA,L]\n";
    if (file_put_contents($public_html . '/.htaccess', $htaccess)) {
        echo "✓ .htaccess criado\n";
    }
    
    // Criar index.php básico
    $index_php = "<?php\n// Sistema LabPro - Reset Completo\necho 'Sistema LabPro - Reset Completo<br>';\necho 'Data: ' . date('Y-m-d H:i:s') . '<br>';\necho 'Status: Pronto para deploy<br>';\n?>";
    if (file_put_contents($public_html . '/index.php', $index_php)) {
        echo "✓ index.php criado\n";
    }
    
    // Criar pasta api
    if (mkdir($public_html . '/api', 0755, true)) {
        echo "✓ Pasta api criada\n";
    }
    
} else {
    echo "✗ Falha ao criar public_html\n";
}

echo "\n=== VERIFICAÇÃO FINAL ===\n";
if (is_dir($public_html)) {
    $perms = fileperms($public_html);
    $octal = substr(sprintf('%o', $perms), -4);
    echo "✓ public_html existe com permissões: $octal\n";
} else {
    echo "✗ public_html não existe\n";
}

echo "\n=== INSTRUÇÕES PARA DEPLOY ===\n";
echo "1. Acesse: https://willianwhiteestevaod1767839682077.2002102.meusitehostgator.com.br/\n";
echo "2. Verifique se a página de reset aparece\n";
echo "3. Execute: git push origin main\n";
echo "4. Configure o deploy automático para public_html\n";

echo "\n=== CONCLUÍDO ===\n";
echo "Finalizado em: " . date('Y-m-d H:i:s') . "\n";
echo "Sistema pronto para deploy do zero!\n";
?>
