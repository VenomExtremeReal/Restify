/**
 * JavaScript principal da aplicação Restify
 */

// Função para adicionar item ao carrinho
function addToCart(serviceId, quantity = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `service_id=${serviceId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.count);
            showAlert('Item adicionado ao carrinho!', 'success');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao adicionar item', 'error');
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
        `/admin/messages/${restaurantId}` : 
        '/restaurant/messages';
    
    chatPolling = setInterval(() => {
        fetch(url)
            .then(response => response.json())
            .then(messages => {
                updateChatMessages(messages);
            })
            .catch(error => console.error('Erro no polling:', error));
    }, 3000);
}

function updateChatMessages(messages) {
    const chatMessages = document.querySelector('.chat-messages');
    if (!chatMessages) return;
    
    chatMessages.innerHTML = '';
    
    messages.forEach(message => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${message.sender_type}`;
        
        messageDiv.innerHTML = `
            <div>${message.message}</div>
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
    .then(() => {
        form.reset();
        // O polling irá atualizar as mensagens automaticamente
    })
    .catch(error => {
        console.error('Erro ao enviar mensagem:', error);
        showAlert('Erro ao enviar mensagem', 'error');
    });
    
    return false;
}

// Formatar data
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('pt-BR');
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
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#e9ecef';
        }
    });
    
    if (!isValid) {
        showAlert('Preencha todos os campos obrigatórios', 'error');
    }
    
    return isValid;
}

// Máscara para WhatsApp
function maskWhatsApp(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    input.value = value;
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar máscara no campo WhatsApp
    const whatsappInput = document.querySelector('input[name="whatsapp"]');
    if (whatsappInput) {
        whatsappInput.addEventListener('input', function() {
            maskWhatsApp(this);
        });
    }
    
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

// Cleanup ao sair da página
window.addEventListener('beforeunload', function() {
    if (chatPolling) {
        clearInterval(chatPolling);
    }
});