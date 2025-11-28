/**
 * JavaScript principal da aplicação Restify
 */

// Função para adicionar item ao carrinho
function addToCart(serviceId, quantity = 1) {
    fetch(window.BASE_URL + '/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `service_id=${serviceId}&quantity=${quantity}`
    })
    .then(response => {
        if (!response.ok) throw new Error('Erro na requisição');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            updateCartCount(data.count);
            showAlert(window.translations?.item_added_to_cart || 'Item adicionado!', 'success');
        } else {
            throw new Error(data.message || 'Erro ao adicionar item');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert(window.translations?.error_adding_item || 'Erro ao adicionar item', 'error');
    });
}

// Atualizar contador do carrinho
function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'flex' : 'none';
    }
}

// Mostrar alertas
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Chat polling para mensagens
let chatPolling = null;

function startChatPolling(restaurantId = null) {
    if (chatPolling) {
        clearInterval(chatPolling);
    }
    
    const url = restaurantId ? 
        `${window.BASE_URL}/admin/messages/${restaurantId}` : 
        `${window.BASE_URL}/restaurant/messages`;
    
    chatPolling = setInterval(() => {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(messages => {
                if (Array.isArray(messages)) {
                    updateChatMessages(messages);
                }
            })
            .catch(error => {
                console.error('Erro no polling:', error);
                // Não mostrar erro para o usuário, apenas logar
            });
    }, 3000);
}

function updateChatMessages(messages) {
    const chatMessages = document.querySelector('.chat-messages');
    if (!chatMessages || !messages) return;
    
    chatMessages.innerHTML = '';
    
    messages.forEach(message => {
        if (!message || !message.sender_type || !message.message) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${message.sender_type}`;
        
        messageDiv.innerHTML = `
            <div>${message.message || ''}</div>
            <div class="message-time">${formatDate(message.created_at)}</div>
        `;
        
        chatMessages.appendChild(messageDiv);
    });
    
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Enviar mensagem do chat
function sendMessage(form) {
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Erro ao enviar mensagem');
        form.reset();
    })
    .catch(error => {
        console.error('Erro ao enviar mensagem:', error);
        showAlert(window.translations?.error_sending_message || 'Erro ao enviar mensagem', 'error');
    });
    
    return false;
}

// Formatar data
function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    
    // Verificar se a data é válida
    if (isNaN(date.getTime())) {
        return dateString;
    }
    
    // Formato brasileiro: dd/mm/aaaa hh:mm
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${day}/${month}/${year} ${hours}:${minutes}`;
}

// Confirmar ações
function confirmAction(message) {
    return confirm(message);
}

// Validação de formulários
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        const isEmpty = !field.value.trim();
        field.style.borderColor = isEmpty ? '#000' : '';
        if (isEmpty) isValid = false;
    });
    
    if (!isValid) {
        showAlert(window.translations?.fill_required_fields || 'Preencha todos os campos', 'error');
    }
    
    return isValid;
}

// Máscara para telefone/WhatsApp
function maskPhone(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        if (value.length <= 10) {
            // Telefone fixo: (11) 1234-5678
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            // Celular: (11) 99999-9999
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
    }
    
    input.value = value;
}

// Máscara para CPF
function maskCPF(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    
    input.value = value;
    
    // Validar CPF se completo
    if (value.replace(/\D/g, '').length === 11) {
        if (!isValidCPF(value.replace(/\D/g, ''))) {
            input.setCustomValidity('CPF inválido');
        } else {
            input.setCustomValidity('');
        }
    }
}

// Validar CPF
function isValidCPF(cpf) {
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
    
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.charAt(9))) return false;
    
    sum = 0;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf.charAt(i)) * (11 - i);
    }
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    return remainder === parseInt(cpf.charAt(10));
}

// Toggle password visibility
function togglePassword(button) {
    const input = button.previousElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

// Gerenciamento de tema
function toggleTheme() {
    const isDark = document.body.classList.contains('dark-theme');
    const newTheme = isDark ? 'light' : 'dark';
    
    document.body.className = newTheme === 'dark' ? 'dark-theme' : '';
    localStorage.setItem('theme', newTheme);
    
    // Atualizar ícone do botão
    const themeIcon = document.querySelector('.theme-icon');
    if (themeIcon) {
        themeIcon.textContent = newTheme === 'dark' ? '🌙' : '☀️';
    }
    
    // Enviar para servidor
    fetch(window.BASE_URL + '/settings/theme', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `theme=${newTheme}`
    });
}

// Mudança de idioma
function changeLanguage(language) {
    fetch(window.BASE_URL + '/settings/language', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `language=${language}`
    })
    .then(() => {
        location.reload();
    });
}

// Inicializar tema salvo
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.className = savedTheme === 'dark' ? 'dark-theme' : '';
    
    // Atualizar ícone do botão
    const themeIcon = document.querySelector('.theme-icon');
    if (themeIcon) {
        themeIcon.textContent = savedTheme === 'dark' ? '🌙' : '☀️';
    }
}

// Toggle do menu de export
function toggleExport() {
    const menu = document.getElementById('exportMenu');
    if (menu) {
        menu.classList.toggle('show');
    }
}

// Fechar menu de export ao clicar fora
document.addEventListener('click', function(e) {
    const exportDropdown = document.querySelector('.export-dropdown');
    const exportMenu = document.getElementById('exportMenu');
    
    if (exportDropdown && exportMenu && !exportDropdown.contains(e.target)) {
        exportMenu.classList.remove('show');
    }
});

// Cleanup ao sair da página
window.addEventListener('beforeunload', function() {
    if (chatPolling) {
        clearInterval(chatPolling);
    }
});

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tema
    initTheme();
    
    // Aplicar máscaras nos campos
    const phoneInputs = document.querySelectorAll('input[name="whatsapp"], input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            maskPhone(this);
        });
    });
    
    const cpfInputs = document.querySelectorAll('input[name*="cpf"]');
    cpfInputs.forEach(input => {
        input.addEventListener('input', function() {
            maskCPF(this);
        });
    });
    
    // Iniciar polling do chat se estiver na página de chat
    if (document.querySelector('.chat-container')) {
        const restaurantId = document.querySelector('[data-restaurant-id]')?.dataset.restaurantId;
        startChatPolling(restaurantId);
    }
    
    // Validação de formulários
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});