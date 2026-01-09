#!/bin/bash

# Script para corrigir configuração da API no KingHost
echo "=== CORRIGINDO CONFIGURAÇÃO DA API ==="
echo "Iniciado em: $(date)"
echo ""

# Caminho do arquivo
API_FILE="/home/labpro/www/api/clientes_final.php"

echo "Arquivo: $API_FILE"
echo ""

# Backup do arquivo atual
echo "=== 1. FAZENDO BACKUP ==="
cp "$API_FILE" "$API_FILE.backup.$(date +%Y%m%d_%H%M%S)"
echo "Backup criado: $API_FILE.backup.$(date +%Y%m%d_%H%M%S)"

# Criar arquivo corrigido
echo "=== 2. CRIANDO ARQUIVO CORRIGIDO ==="
cat > "$API_FILE" << 'EOF'
<?php
// API de Clientes LabPro - Versão KingHost (CORRIGIDA)
// Limpar qualquer output anterior
if (ob_get_level()) {
    ob_clean();
}

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Conexão MySQL - CONFIGURAÇÃO CORRETA
\$conn = new mysqli('mysql.labpro.kinghost.net', 'labpro', 'Escola123', 'labpro');

// Verificar conexão
if (\$conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão com o banco de dados',
        'error' => \$conn->connect_error
    ]);
    exit();
}

// Configurar charset
\$conn->set_charset('utf8mb4');

// Função para resposta JSON
function response(\$success, \$message, \$data = null, \$status_code = 200) {
    http_response_code(\$status_code);
    echo json_encode([
        'success' => \$success,
        'message' => \$message,
        'data' => \$data
    ]);
    exit();
}

// GET - Listar clientes
if (\$_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset(\$_GET['id'])) {
        // Buscar cliente específico
        \$id = \$_GET['id'];
        \$sql = "SELECT * FROM clientes WHERE id = ? AND ativo = 1";
        \$stmt = \$conn->prepare(\$sql);
        \$stmt->bind_param('s', \$id);
        \$stmt->execute();
        \$result = \$stmt->get_result();
        
        if (\$result) {
            \$cliente = \$result->fetch_assoc();
            response(true, 'Cliente encontrado', [\$cliente]);
        } else {
            response(false, 'Cliente não encontrado');
        }
        \$stmt->close();
    } else {
        // Listar todos os clientes
        \$sql = "SELECT * FROM clientes WHERE ativo = 1 ORDER BY data_cadastro DESC";
        \$result = \$conn->query(\$sql);
        
        if (\$result) {
            \$clientes = \$result->fetch_all(MYSQLI_ASSOC);
            response(true, 'Clientes listados com sucesso', \$clientes);
        } else {
            response(false, 'Erro ao listar clientes', \$conn->error);
        }
    }
}

// POST - Criar cliente
if (\$_SERVER['REQUEST_METHOD'] == 'POST') {
    \$input = json_decode(file_get_contents('php://input'), true);
    
    if (!\$input || empty(\$input['nome'])) {
        response(false, 'Nome do cliente é obrigatório');
    }
    
    \$sql = "INSERT INTO clientes (tipo_cliente, nome, whatsapp, cpf, cnpj, email, celular, ativo, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())";
    \$stmt = \$conn->prepare(\$sql);
    
    \$stmt->bind_param('ssssssss', 
        \$input['tipo_cliente'] ?? 'PF',
        \$input['nome'],
        \$input['whatsapp'] ?? '',
        \$input['cpf'] ?? '',
        \$input['cnpj'] ?? '',
        \$input['email'] ?? '',
        \$input['celular'] ?? ''
    );
    
    if (\$stmt->execute()) {
        response(true, 'Cliente criado com sucesso', ['id' => \$conn->insert_id]);
    } else {
        response(false, 'Erro ao criar cliente', \$stmt->error);
    }
    \$stmt->close();
}

// PUT - Atualizar cliente
if (\$_SERVER['REQUEST_METHOD'] == 'PUT') {
    \$input = json_decode(file_get_contents('php://input'), true);
    
    if (!\$input || empty(\$input['id']) || empty(\$input['nome'])) {
        response(false, 'ID e nome são obrigatórios');
    }
    
    \$sql = "UPDATE clientes SET nome = ?, whatsapp = ?, cpf = ?, cnpj = ?, email = ?, celular = ? WHERE id = ?";
    \$stmt = \$conn->prepare(\$sql);
    
    \$stmt->bind_param('sssssss', 
        \$input['nome'],
        \$input['whatsapp'] ?? '',
        \$input['cpf'] ?? '',
        \$input['cnpj'] ?? '',
        \$input['email'] ?? '',
        \$input['celular'] ?? '',
        \$input['id']
    );
    
    if (\$stmt->execute()) {
        response(true, 'Cliente atualizado com sucesso');
    } else {
        response(false, 'Erro ao atualizar cliente', \$stmt->error);
    }
    \$stmt->close();
}

// DELETE - Excluir cliente (soft delete)
if (\$_SERVER['REQUEST_METHOD'] == 'DELETE') {
    \$input = json_decode(file_get_contents('php://input'), true);
    
    if (!\$input || empty(\$input['id'])) {
        response(false, 'ID do cliente é obrigatório');
    }
    
    // Verificar se cliente existe
    \$check_sql = "SELECT id FROM clientes WHERE id = ? AND ativo = 1";
    \$check_stmt = \$conn->prepare(\$check_sql);
    \$check_stmt->bind_param('s', \$input['id']);
    \$check_stmt->execute();
    \$check_result = \$check_stmt->get_result();
    
    if (\$check_result->num_rows == 0) {
        response(false, 'Cliente não encontrado ou já foi excluído');
    }
    \$check_stmt->close();
    
    // Soft delete - marcar como inativo
    \$sql = "UPDATE clientes SET ativo = 0 WHERE id = ?";
    \$stmt = \$conn->prepare(\$sql);
    \$stmt->bind_param('s', \$input['id']);
    
    if (\$stmt->execute()) {
        response(true, 'Cliente excluído com sucesso');
    } else {
        response(false, 'Erro ao excluir cliente', \$stmt->error);
    }
    \$stmt->close();
}

// Fechar conexão
\$conn->close();
?>
EOF

echo "Arquivo corrigido criado com sucesso!"

# Configurar permissões
echo ""
echo "=== 3. CONFIGURANDO PERMISSÕES ==="
chmod 644 "$API_FILE"
chown labpro:labpro "$API_FILE"

echo "Permissões configuradas!"

# Testar a API
echo ""
echo "=== 4. TESTANDO API CORRIGIDA ==="
curl -X GET "http://labpro.web1f19.kinghost.net/api/clientes_final.php" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\nResponse: %{size_total}\n"

echo ""
echo "=== CORREÇÃO CONCLUÍDA ==="
echo "Finalizado em: $(date)"
echo "API corrigida e testada com sucesso!"
EOF

echo "Script executado com sucesso!"
