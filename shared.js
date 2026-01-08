// Funções compartilhadas entre todos os arquivos HTML
// LabPro - Sistema Laboratorial Dental

// Função para alternar tema (global)
function toggleTheme() {
    const body = document.body;
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        if (themeIcon) themeIcon.textContent = '🌙';
        if (themeText) themeText.textContent = 'Dark';
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        if (themeIcon) themeIcon.textContent = '☀️';
        if (themeText) themeText.textContent = 'Light';
        localStorage.setItem('theme', 'dark');
    }
}

// Carregar tema salvo (global)
function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    const body = document.body;
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    
    if (savedTheme === 'dark') {
        body.classList.add('dark-theme');
        if (themeIcon) themeIcon.textContent = '☀️';
        if (themeText) themeText.textContent = 'Light';
    } else {
        body.classList.remove('dark-theme');
        if (themeIcon) themeIcon.textContent = '🌙';
        if (themeText) themeText.textContent = 'Dark';
    }
}

// Função para alternar abas
function showTab(tabName) {
    // Esconder todas as abas
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remover classe ativo de todos os botões
    document.querySelectorAll('.nav-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar aba selecionada
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Ativar botão correspondente
    const activeButton = document.querySelector(`[onclick="showTab('${tabName}')"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
}

// Função para mostrar páginas
function showPage(pageName) {
    // Para páginas do mesmo módulo, mostrar abas internas
    const financeiroPages = ['contas-receber', 'contas-pagar', 'conta-bancaria', 'caixa-fisico'];
    const estoquePages = ['produtos', 'orcamento'];
    const cadastrosPages = ['clientes', 'tabela-precos', 'setores', 'materiais'];
    const producaoPages = ['ordem-servico', 'controle-producao', 'agenda-producao'];
    const relatorioPages = ['fluxo-caixa', 'relatorio-producao', 'relatorio-estoque', 'logs'];
    const configPages = ['configuracoes-gerais', 'usuarios', 'modelo-ficha'];
    
    // Verificar se é uma aba interna do módulo atual
    if (financeiroPages.includes(pageName) && window.location.pathname.includes('financeiro.html')) {
        showTab(pageName);
        return;
    }
    
    if (estoquePages.includes(pageName) && window.location.pathname.includes('estoque.html')) {
        showTab(pageName);
        return;
    }
    
    if (cadastrosPages.includes(pageName) && window.location.pathname.includes('cadastros.html')) {
        showTab(pageName);
        return;
    }
    
    if (producaoPages.includes(pageName) && window.location.pathname.includes('producao.html')) {
        showTab(pageName);
        return;
    }
    
    if (relatorioPages.includes(pageName) && window.location.pathname.includes('relatorio.html')) {
        showTab(pageName);
        return;
    }
    
    if (configPages.includes(pageName) && window.location.pathname.includes('configuracoes.html')) {
        showTab(pageName);
        return;
    }
    
    // Redirecionar para páginas separadas
    const pageUrls = {
        'contas-receber': '/financeiro.html',
        'contas-pagar': '/financeiro.html',
        'conta-bancaria': '/financeiro.html',
        'caixa-fisico': '/financeiro.html',
        'clientes': '/cadastros.html',
        'tabela-precos': '/cadastros.html',
        'setores': '/cadastros.html',
        'materiais': '/cadastros.html',
        'produtos': '/estoque.html',
        'orcamento': '/estoque.html',
        'ordem-servico': '/producao.html',
        'controle-producao': '/producao.html',
        'agenda-producao': '/producao.html',
        'fluxo-caixa': '/relatorio.html',
        'relatorio-producao': '/relatorio.html',
        'relatorio-estoque': '/relatorio.html',
        'logs': '/relatorio.html',
        'configuracoes-gerais': '/configuracoes.html',
        'usuarios': '/configuracoes.html',
        'modelo-ficha': '/configuracoes.html'
    };
    
    // Se a página tiver um URL separado, redirecionar
    if (pageUrls[pageName]) {
        window.location.href = pageUrls[pageName];
        return;
    }
    
    // Para página início, redirecionar para o dashboard
    if (pageName === 'inicio') {
        window.location.href = '/dashboard.html';
        return;
    }
}

// Função para alternar menu lateral
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('collapsed');
    }
}

// Função para alternar submenus
function toggleMenu(element) {
    const submenu = element.nextElementSibling;
    if (submenu && submenu.classList.contains('submenu')) {
        submenu.classList.toggle('active');
        const arrow = element.querySelector('.arrow');
        if (arrow) {
            arrow.style.transform = submenu.classList.contains('active') ? 'rotate(90deg)' : 'rotate(0deg)';
        }
    }
}

// Função para alternar tema
function toggleTheme() {
    const body = document.body;
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        themeIcon.textContent = '🌙';
        themeText.textContent = 'Dark';
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.add('dark-theme');
        themeIcon.textContent = '☀️';
        themeText.textContent = 'Light';
        localStorage.setItem('theme', 'dark');
    }
}

// Função para carregar tema salvo
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('theme');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if (themeIcon) themeIcon.textContent = '☀️';
        if (themeText) themeText.textContent = 'Light';
    } else {
        if (themeIcon) themeIcon.textContent = '🌙';
        if (themeText) themeText.textContent = 'Dark';
    }
}

// Função de logout
function logout() {
    if (confirm('Tem certeza que deseja sair?')) {
        localStorage.removeItem('authToken');
        localStorage.removeItem('userName');
        window.location.href = 'index.html';
    }
}

// Função para atualizar data
function updateDate() {
    const dateElements = document.querySelectorAll('.current-date');
    const now = new Date();
    const formattedDate = now.toLocaleDateString('pt-BR');
    
    dateElements.forEach(element => {
        element.textContent = formattedDate;
    });
}

// Função para formatar moeda
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Função para validar formulários
function validateForm(formData) {
    const requiredFields = ['patientName', 'boxNumber', 'dentistId'];
    const missingFields = requiredFields.filter(field => !formData[field]);
    
    if (missingFields.length > 0) {
        alert('Por favor, preencha todos os campos obrigatórios!');
        return false;
    }
    return true;
}

// Função para mostrar notificações
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Inicialização comum
document.addEventListener('DOMContentLoaded', function() {
    loadSavedTheme();
    updateDate();
    setInterval(updateDate, 60000);
    
    // Expandir menu ativo
    const activeMenu = document.querySelector('.menu-title.active');
    if (activeMenu) {
        toggleMenu(activeMenu);
    }
});

// Adicionar estilos CSS para notificações
const notificationStyles = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    }
    
    .notification-success {
        background: #28a745;
    }
    
    .notification-error {
        background: #dc3545;
    }
    
    .notification-info {
        background: #17a2b8;
    }
    
    .notification-warning {
        background: #ffc107;
        color: #000;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;

// Adicionar estilos ao head
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);
