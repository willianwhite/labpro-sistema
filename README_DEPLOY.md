# Deploy do Zero - LabPro Sistema

## Passos para Reset Completo

### 1. Reset no cPanel
1. Acesse cPanel → File Manager
2. Vá para `/home1/willi767/`
3. Renomeie `public_html` para `backup_old`
4. Crie nova pasta `public_html` (permissões 755)
5. Execute o script de reset: `https://willianwhiteestevaod1767839682077.2002102.meusitehostgator.com.br/api/reset_system.php`

### 2. Deploy Automático
1. No cPanel → Git™ Version Control
2. Clone o repositório: `https://github.com/willianwhite/labpro-sistema.git`
3. Configure deploy para `/home1/willi767/public_html`
4. Execute: `git push origin main`

### 3. Verificação
1. Acesse: `https://willianwhiteestevaod1767839682077.2002102.meusitehostgator.com.br/`
2. Verifique se `cadastros.html` funciona
3. Teste a API: `https://willianwhiteestevaod1767839682077.2002102.meusitehostgator.com.br/api/test_minimal.php`

### 4. Configuração Final
1. Verifique permissões (755 para dirs, 644 para arquivos)
2. Teste conexão com banco: `https://willianwhiteestevaod1767839682077.2002102.meusitehostgator.com.br/api/check_database.php`
3. Teste cadastro de cliente

## Estrutura Esperada
```
public_html/
├── cadastros.html
├── producao.html
├── api/
│   ├── clientes_final.php
│   ├── test_minimal.php
│   ├── check_database.php
│   └── reset_system.php
└── README_DEPLOY.md
```

## Troubleshooting
- Se erro 500: verifique logs em `/api/get_logs.php`
- Se permissões: execute `/api/fix_all_permissions.php`
- Se banco: execute `/api/check_database.php`
