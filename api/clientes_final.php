<?php
require_once 'config.php';

// POST /api/clientes - Criar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);
    
    if (!$data) {
        response(false, 'JSON inválido', null, 400);
    }
    
    // Validação básica
    if (empty($data['tipoCliente']) || empty($data['nome']) || empty($data['whatsapp'])) {
        response(false, 'Campos obrigatórios: tipoCliente, nome, whatsapp', null, 400);
    }
    
    if (empty($data['cnpj']) && empty($data['cpf'])) {
        response(false, 'Preencha CNPJ ou CPF', null, 400);
    }
    
    // Preparar query
    $sql = "INSERT INTO clientes (
        tipo_cliente, nome, cnpj, cpf, email, celular, whatsapp,
        cep, rua, numero, bairro, cidade, uf, complemento,
        tabela_precos, desconto_geral
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    $params = [
        $data['tipoCliente'],
        $data['nome'],
        $data['cnpj'] ?? null,
        $data['cpf'] ?? null,
        $data['email'] ?? null,
        $data['celular'] ?? null,
        $data['whatsapp'],
        $data['cep'] ?? null,
        $data['rua'] ?? null,
        $data['numero'] ?? null,
        $data['bairro'] ?? null,
        $data['cidade'] ?? null,
        $data['uf'] ?? null,
        $data['complemento'] ?? null,
        $data['tabelaPrecos'] ?? 'padrao',
        $data['descontoGeral'] ?? 0
    ];
    
    if ($stmt->execute($params)) {
        $id = $conn->insert_id;
        
        // Buscar cliente criado
        $select_sql = "SELECT * FROM clientes WHERE id = ?";
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->execute([$id]);
        $cliente = $select_stmt->fetch_assoc();
        
        log_message("Cliente criado: ID $id - {$cliente['nome']}");
        response(true, 'Cliente criado com sucesso', $cliente, 201);
    } else {
        log_message("Erro ao criar cliente: " . $stmt->error);
        response(false, 'Erro ao criar cliente', null, 500);
    }
}

// GET /api/clientes - Listar clientes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $busca = isset($_GET['busca']) ? $_GET['busca'] : null;
    
    if ($busca) {
        $sql = "SELECT id, tipo_cliente, nome, cnpj, cpf, email, celular, whatsapp,
                       cep, rua, numero, bairro, cidade, uf, complemento,
                       tabela_precos, desconto_geral, data_cadastro, ativo
                FROM clientes 
                WHERE ativo = 1 AND nome LIKE ?
                ORDER BY nome ASC 
                LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["%$busca%", $limite]);
    } else {
        $sql = "SELECT id, tipo_cliente, nome, cnpj, cpf, email, celular, whatsapp,
                       cep, rua, numero, bairro, cidade, uf, complemento,
                       tabela_precos, desconto_geral, data_cadastro, ativo
                FROM clientes 
                WHERE ativo = 1 
                ORDER BY data_cadastro DESC 
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$limite, $offset]);
    }
    
    $clientes = $stmt->fetch_all(MYSQLI_ASSOC);
    
    log_message("Listados " . count($clientes) . " clientes");
    response(true, 'Clientes listados com sucesso', $clientes, 200);
}

// Se não for nenhum método acima
response(false, 'Método não permitido', null, 405);
?>
