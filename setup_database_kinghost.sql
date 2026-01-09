-- Script para criar banco e tabela no KingHost
-- Execute no PuTTY: mysql -u labpro -p'Escola123!' labpro < setup_database_kinghost.sql

-- Criar tabela clientes se não existir
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_cliente VARCHAR(50) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    whatsapp VARCHAR(20),
    cpf VARCHAR(20),
    cnpj VARCHAR(20),
    email VARCHAR(255),
    celular VARCHAR(20),
    ativo TINYINT(1) DEFAULT 1,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Inserir um cliente de teste
INSERT INTO clientes (tipo_cliente, nome, whatsapp, email, celular) 
VALUES ('PF', 'Cliente Teste KingHost', '11999999999', 'teste@kinghost.com', '11988888888')
ON DUPLICATE KEY UPDATE nome = 'Cliente Teste KingHost';

-- Mostrar resultado
SELECT 'Tabela clientes criada com sucesso!' as status;
SELECT COUNT(*) as total_clientes FROM clientes;
