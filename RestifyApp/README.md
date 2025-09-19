# Restify - Sistema de Serviços para Restaurantes

Sistema web desenvolvido em PHP puro com arquitetura MVC para oferecer serviços digitais para restaurantes.

## 🚀 Funcionalidades

### Para Restaurantes
- Cadastro e login
- Visualização e contratação de serviços
- Carrinho de compras
- Acompanhamento de pedidos
- Chat com suporte

### Para Administradores
- Dashboard com estatísticas
- Gerenciamento de pedidos
- Visualização de restaurantes cadastrados
- Sistema de chat para atendimento

### Serviços Oferecidos
- Site com hospedagem (R$ 299,99)
- Instagram + 5 posts (R$ 199,99)
- Google Maps + 10 QR codes (R$ 149,99)
- Cardápio online (R$ 99,99)
- Pacote básico (R$ 449,99)
- Pacote completo (R$ 649,99)

## 🛠️ Tecnologias

- **Backend:** PHP 7.4+
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Banco de dados:** MySQL
- **Servidor web:** Apache
- **Arquitetura:** MVC tradicional

## 📁 Estrutura do Projeto

```
RestifyApp/
├── app/
│   ├── controllers/     # Controllers MVC
│   ├── models/         # Models de dados
│   ├── views/          # Views (HTML/PHP)
│   ├── services/       # Lógica de negócio
│   └── repositories/   # Acesso a dados
├── config/             # Configurações
├── database/           # Scripts SQL
└── public/             # Arquivos públicos
    ├── css/           # Estilos
    ├── js/            # JavaScript
    └── index.php      # Ponto de entrada
```

## ⚙️ Instalação

1. **Clone o projeto:**
   ```bash
   git clone [url-do-repositorio]
   cd RestifyApp
   ```

2. **Configure o banco de dados:**
   - Crie um banco MySQL chamado `restify`
   - Execute o script `database/schema.sql`
   - Ajuste as credenciais em `config/database.php`

3. **Configure o servidor web:**
   - Aponte o DocumentRoot para a pasta `public/`
   - Certifique-se que o mod_rewrite está ativo

4. **Acesse o sistema:**
   - URL: `http://localhost/RestifyApp/public`

## 👤 Credenciais de Teste

### Administrador
- **Email:** admin@restify.com
- **Senha:** admin123

### Restaurante de Teste
Cadastre um novo restaurante através da tela de registro.

## 🎨 Design

- **Mobile-first:** Interface otimizada para dispositivos móveis
- **Responsivo:** Adapta-se a diferentes tamanhos de tela
- **Moderno:** Design limpo e profissional
- **Acessível:** Cores e contrastes adequados

## 💬 Sistema de Chat

- **Polling:** Atualização automática a cada 3 segundos
- **Tempo real:** Comunicação fluida entre restaurante e admin
- **Histórico:** Todas as mensagens são salvas
- **Interface intuitiva:** Chat similar a aplicativos populares

## 🔒 Segurança

- Validação de dados no frontend e backend
- Proteção contra SQL injection (PDO)
- Sanitização de outputs (htmlspecialchars)
- Controle de acesso por sessões

## 📱 Responsividade

- **Desktop:** Layout em grid com múltiplas colunas
- **Tablet:** Adaptação automática do grid
- **Mobile:** Layout em coluna única, navegação otimizada

## 🚀 Como Usar

1. **Restaurante:**
   - Cadastre-se na plataforma
   - Navegue pelos serviços disponíveis
   - Adicione itens ao carrinho
   - Finalize o pedido
   - Acompanhe o progresso via chat

2. **Administrador:**
   - Faça login com as credenciais admin
   - Visualize pedidos no dashboard
   - Atualize status dos pedidos
   - Responda mensagens dos restaurantes

## 🔧 Manutenção

- **Logs:** Erros são exibidos em desenvolvimento
- **Backup:** Faça backup regular do banco de dados
- **Updates:** Mantenha o PHP e MySQL atualizados

## 📞 Suporte

Para dúvidas ou problemas:
- Use o sistema de chat interno
- Horário: Segunda a Sexta, 8h às 18h

---

**Desenvolvido com ❤️ para impulsionar restaurantes no digital**