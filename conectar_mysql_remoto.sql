-- Script para conectar ao MySQL remoto da KingHost
-- Execute no PuTTY: mysql -h mysql.labpro.kinghost.net -u labpro -p'Escola123' labpro < conectar_mysql_remoto.sql

-- Criar tabela clientes no servidor remoto
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_cliente VARCHAR(50) NOT NULL DEFAULT 'PF',
    nome VARCHAR(255) NOT NULL,
    whatsapp VARCHAR(20),
    cpf VARCHAR(20),
    cnpj VARCHAR(20),
    email VARCHAR(255),
    celular VARCHAR(20),
    ativo TINYINT(1) DEFAULT 1,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_ativo (ativo),
    INDEX idx_tipo_cliente (tipo_cliente)
);

-- Inserir clientes de teste
INSERT INTO clientes (tipo_cliente, nome, whatsapp, email, celular) VALUES
('PF', 'João Silva - KingHost', '11999999999', 'joao@labpro.com', '11988888888'),
('PJ', 'Empresa ABC KingHost', '22999999999', 'contato@empresaabc.com', '22988888888'),
('PF', 'Maria Santos - KingHost', '11977777777', 'maria@labpro.com', '11966666666')
ON DUPLICATE KEY UPDATE nome = VALUES(nome);

-- Mostrar resultado
SELECT 'Tabela clientes criada no MySQL remoto!' AS status;
SELECT COUNT(*) AS total_clientes FROM clientes;
SELECT * FROM clientes LIMIT 3;
