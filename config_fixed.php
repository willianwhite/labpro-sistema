<?php
// Arquivo de Configuração Centralizada - LabPro (CORRIGIDO)
// Segurança: Não commitar no repositório Git
// Adicionar ao .gitignore

// Ambientes disponíveis
$config = [
    'development' => [
        'db_host' => 'localhost',
        'db_name' => 'labpro_dev',
        'db_user' => 'root',
        'db_pass' => 'root',
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
        'db_host' => 'mysql51-farm1.kinghost.net',  // CORRIGIDO!
        'db_name' => 'labpro',
        'db_user' => 'labpro',
        'db_pass' => 'Escola123!',
        'api_url' => 'http://labpro.web1f19.kinghost.net/api',
        'site_url' => 'http://labpro.web1f19.kinghost.net',
        'debug' => false
    ],
    'local' => [
        'db_host' => 'localhost',
        'db_name' => 'labpro',
        'db_user' => 'labpro',
        'db_pass' => 'Escola123!',
        'api_url' => 'http://localhost/api',
        'site_url' => 'http://localhost',
        'debug' => true
    ]
];

// Detectar ambiente automaticamente
function detectEnvironment() {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $server_name = $_SERVER['SERVER_NAME'] ?? '';
    
    // Verificar ambiente baseado no host
    if (strpos($host, 'localhost') !== false || strpos($server_name, 'localhost') !== false) {
        return 'local';
    } elseif (strpos($host, 'staging') !== false || strpos($server_name, 'staging') !== false) {
        return 'staging';
    } elseif (strpos($host, 'labpro.web1f19.kinghost.net') !== false) {
        return 'production';
    } else {
        return 'development'; // padrão
    }
}

// Obter configuração do ambiente atual
$environment = detectEnvironment();
$current_config = $config[$environment];

// Definir constantes para uso no sistema
define('DB_HOST', $current_config['db_host']);
define('DB_NAME', $current_config['db_name']);
define('DB_USER', $current_config['db_user']);
define('DB_PASSWORD', $current_config['db_pass']);
define('API_URL', $current_config['api_url']);
define('SITE_URL', $current_config['site_url']);
define('DEBUG_MODE', $current_config['debug']);
define('ENVIRONMENT', $environment);

// Para debug (remover em produção)
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Função para obter configuração
function getConfig($key = null) {
    global $current_config;
    
    if ($key === null) {
        return $current_config;
    }
    
    return isset($current_config[$key]) ? $current_config[$key] : null;
}

// Função para verificar se está em produção
function isProduction() {
    return defined('ENVIRONMENT') && ENVIRONMENT === 'production';
}

// Função para obter URL base
function getBaseUrl() {
    return defined('SITE_URL') ? SITE_URL : 'http://localhost';
}

// Função para obter URL da API
function getApiUrl() {
    return defined('API_URL') ? API_URL : 'http://localhost/api';
}

// Log do ambiente (apenas em desenvolvimento)
if (DEBUG_MODE) {
    error_log("LabPro Environment: " . ENVIRONMENT);
    error_log("Database Host: " . DB_HOST);
    error_log("Database Name: " . DB_NAME);
    error_log("API URL: " . API_URL);
    error_log("Site URL: " . SITE_URL);
}

// Retornar configuração como JSON (para uso em APIs)
if (isset($_GET['config'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'environment' => ENVIRONMENT,
        'config' => $current_config,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

?>
<!-- 
SEGURANÇA:
1. NUNCA COMMITAR ESTE ARQUIVO COM SENHAS
2. ADICIONAR AO .GITIGNORE
3. MANTER BACKUP SEPARADO E SEGURO
4. ROTACIONAR SENHAS PERIODICAMENTE
-->
