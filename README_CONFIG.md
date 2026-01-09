# Sistema de Configuração Centralizada - LabPro

## 📋 Visão Geral

Sistema de configuração centralizada para gerenciar múltiplos ambientes (desenvolvimento, staging, produção) de forma segura e organizada.

## 🎯 Objetivos

- **Centralizar** configurações em um único arquivo
- **Segurança:** Senhas e IPs não commitados no Git
- **Flexibilidade:** Mudar entre ambientes facilmente
- **Manutenção:** Atualizar configurações sem alterar código
- **Versionamento:** Controle de alterações de configuração

## 📁 Estrutura de Arquivos

```
labpro-deploy/
├── config.php                 # Configuração central (NÃO COMMITAR)
├── .gitignore                # Ignorar arquivos sensíveis
├── api/
│   ├── config_loader.php     # Carregador da configuração
│   ├── config_example.php   # Exemplo de configuração
│   └── clientes_final.php    # Usa config_loader.php
├── cadastros.html           # Usa URLs dinâmicas
├── index.html              # Usa URLs dinâmicas
└── README_CONFIG.md         # Documentação
```

## 🔧 Como Usar

### 1. Configuração Inicial

```bash
# Copiar exemplo para configuração real
cp api/config_example.php config.php

# Editar configuração
nano config.php
```

### 2. Ambientes Disponíveis

#### **Development (Local)**
- Banco: MySQL local
- Debug: Ativado
- URLs: localhost

#### **Staging (Testes)**
- Banco: MySQL staging
- Debug: Ativado
- URLs: staging.labpro.kinghost.net

#### **Production (KingHost)**
- Banco: MySQL remoto KingHost
- Debug: Desativado
- URLs: labpro.web1f19.kinghost.net

### 3. Segurança

#### **🔒 Proteção de Dados**
- `config.php` nunca commitado
- Adicionado ao `.gitignore`
- Senhas separadas do código
- Backup criptografado

#### **🔒 Permissões**
- Arquivo: 600 (apenas dono)
- Diretório: 700 (apenas dono)
- Never público ou world-writable

### 4. Uso no Código

#### **PHP**
```php
// Incluir carregador
require_once 'api/config_loader.php';

// Usar constantes
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// URLs dinâmicas
$api_url = getApiUrl();
$site_url = getBaseUrl();
```

#### **JavaScript**
```javascript
// Obter URLs dinâmicas (se necessário)
const API_URL = '<?= API_URL ?>';
const SITE_URL = '<?= SITE_URL ?>';
```

## 🚀 Deploy

### 1. Ambiente Local
```bash
# Usar config local
cp config_example.php config.php
# Editar com dados locais
```

### 2. Ambiente Produção
```bash
# Upload do config.php (com dados reais)
# Nunca fazer upload de config_example.php
# Manter config.php seguro no servidor
```

## 📋 Boas Práticas

1. **Nunca commitar** `config.php` com senhas
2. **Sempre usar** `.gitignore` para arquivos sensíveis
3. **Manter backup** da configuração
4. **Rotacionar senhas** periodicamente
5. **Usar variáveis** de ambiente em vez de hardcode

## 🔧 Scripts Auxiliares

### Criar ambiente
```bash
# Script para criar ambiente
./setup_env.sh development
```

### Testar configuração
```bash
# Verificar configuração atual
curl "http://seu-site.com/api/config_loader.php?config=true"
```

### Backup da configuração
```bash
# Script para backup
./backup_config.sh
```

---
**IMPORTANTE:** Mantenha este arquivo seguro e atualizado!
