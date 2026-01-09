<?php
// Exemplo de configuração - NÃO USAR EM PRODUÇÃO
// Copiar para config.php e ajustar conforme necessário

// Ambiente de desenvolvimento
return [
    'development' => [
        'db_host' => 'localhost',
        'db_name' => 'labpro_dev',
        'db_user' => 'root',
        'db_pass' => 'sua_senha_aqui',
        'api_url' => 'http://localhost:3000/api',
        'site_url' => 'http://localhost:3000',
        'debug' => true
    ],
    'staging' => [
        'db_host' => 'mysql.staging.kinghost.net',
        'db_name' => 'labpro_staging',
        'db_user' => 'labpro_staging',
        'db_pass' => 'senha_staging_123',
        'api_url' => 'http://staging.labpro.kinghost.net/api',
        'site_url' => 'http://staging.labpro.kinghost.net',
        'debug' => true
    ],
    'production' => [
        'db_host' => 'mysql.production.kinghost.net',
        'db_name' => 'labpro',
        'db_user' => 'labpro',
        'db_pass' => 'senha_producao_muito_segura',
        'api_url' => 'https://labpro.kinghost.net/api',
        'site_url' => 'https://labpro.kinghost.net',
        'debug' => false
    ]
];
?>
