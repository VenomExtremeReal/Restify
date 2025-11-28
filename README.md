# Restify - Sistema de Serviços para Restaurantes

## Descrição

Sistema web desenvolvido em PHP puro com arquitetura MVC para oferecer serviços digitais para restaurantes. O sistema permite que restaurantes contratem serviços como criação de sites, gerenciamento de redes sociais, cardápios online e integração com Google Maps, com sistema completo de pagamentos via PIX, cartão de crédito, boleto e carnê.

## Integrantes

- Artur Braga de Azevedo - 22302123
- Lucas Ferreira Santos - 22301410
- Júlia Braga Lemos - 22302344
- Júlio Andrade Nogueira - 22300988
- Bernardo de Oliveira Andrade - 22300783
- Matheus Pedro Procopio - 22403337

## Estrutura de Diretórios

```
RestifyApp/
├── app/                    # Código-fonte principal
│   ├── controllers/        # Controllers MVC
│   ├── models/            # Models de dados
│   ├── repositories/      # Camada de persistência (Repository Pattern)
│   ├── services/          # Lógica de negócio e Design Patterns
│   └── views/             # Views (HTML/PHP)
├── config/                # Configurações do sistema
├── database/              # Scripts SQL e banco SQLite
├── lang/                  # Arquivos de internacionalização
├── public/                # Arquivos públicos e ponto de entrada
├── composer.json          # Dependências do projeto
└── README.md             # Documentação do projeto
```

## ⚠️ IMPORTANTE - Pasta vendor/ e GitHub

A pasta `vendor/` **NÃO está incluída** no GitHub (conforme boas práticas).
Ela contém as dependências do Composer e será criada automaticamente ao executar `composer install`.

### Por que não incluir vendor/ no Git?
- Pasta muito grande (milhares de arquivos)
- Dependências são gerenciadas pelo composer.json
- Evita conflitos entre diferentes ambientes
- Padrão da comunidade PHP

### Arquivos ignorados (.gitignore):
```
/vendor/
/config/efi_credentials.php
/config/certificates/*.p12
/database/restify.db
*.log
```

### Para outros desenvolvedores:
1. Clone o repositório
2. Execute `composer install` (obrigatório)
3. Configure o servidor web
4. Acesse o sistema

## Como Executar o Projeto

### 1. Pré-requisitos

- PHP 7.4 ou superior
- Servidor web (Apache/Nginx) ou WAMP/XAMPP
- SQLite (incluído no PHP)
- Composer (para dependências do SDK Efí Bank)

### 2. Instalação

```bash
# Clone o repositório
git clone https://github.com/usuario/RestifyApp.git

# Acesse a pasta do projeto
cd RestifyApp

# Instale as dependências
composer install
```

### 3. Configuração

1. Configure o servidor web para apontar para a pasta `public/`
2. Certifique-se que o mod_rewrite está ativo
3. O banco SQLite será criado automaticamente no primeiro acesso

### 4. Execução

```bash
# Para WAMP/XAMPP
# Coloque o projeto na pasta www/htdocs
# Acesse: http://localhost/RestifyApp/public

# Para servidor PHP built-in
cd public
php -S localhost:8000
```

### 5. Acesso

- URL local: http://localhost/RestifyApp/public
- Usuário admin: admin@restify.com
- Senha admin: admin123

---

## Checklist das Funcionalidades

1. ✔️ **Cadastro de Usuários** - Formulário completo com nome do restaurante, dono, email, tipo de comida e endereço
2. ✔️ **Mostrar/Ocultar Senha** - Botão para visualizar senha nos campos de login e cadastro
3. ✔️ **Login/Logout** - Sistema de autenticação com sessões
4. ✔️ **Solicitação via Chat** - Chat para alterações de produtos e suporte
5. ✔️ **Visualização e Contratação de Serviços** - Catálogo completo de serviços
6. ✔️ **Acompanhamento de Pedidos** - Sistema de status e tracking
7. ✔️ **Carrinho de Compras** - Adicionar/remover itens do carrinho
8. ✔️ **Suporte Múltiplos Idiomas** - PT, EN, ES
9. ✔️ **Exportação CSV** - Relatórios de pedidos e restaurantes
10. ✔️ **Criação de Novos Serviços** - Painel administrativo
11. ✔️ **Alternância de Temas** - Modo claro/escuro
12. ✔️ **Chat com Suporte** - Comunicação em tempo real
13. ✔️ **Adição de Pacotes e Serviços** - Gerenciamento completo
14. ✔️ **Cardápio via QR Code** - Integração com Google Maps e QR codes
15. ✔️ **Pacotes e Serviços Individuais** - Diferentes opções de contratação
16. ✔️ **Pagamentos Múltiplos** - PIX, Cartão, Boleto, Carnê via Efí Bank
17. ✔️ **Design Responsivo** - Adaptação a diferentes dispositivos
18. ✔️ **Gerenciamento de Pedidos** - Controle completo de status
19. ✔️ **Visualização de Restaurantes** - Lista e detalhes dos cadastrados
20. ✔️ **Dashboard com Estatísticas** - Painéis admin e restaurante com métricas

---

## Design Patterns Aplicados na Camada de Domínio

### 🔹 Singleton
- **Uso**: Conexão única ao banco de dados na classe `Database`
- **Justificativa**: Evita múltiplas instâncias de conexão, economiza recursos e garante consistência
- **Implementação**: `config/database.php`

### 🔹 Repository Pattern
- **Uso**: Camada de abstração para acesso a dados
- **Justificativa**: Separa lógica de negócio da persistência, facilita testes e manutenção
- **Implementação**: `app/repositories/` (RestaurantRepository, OrderRepository, etc.)

### 🔹 Factory Pattern
- **Uso**: Criação de serviços de pagamento baseado no tipo
- **Justificativa**: Centraliza criação de objetos complexos, facilita extensibilidade
- **Implementação**: `app/services/PaymentServiceFactory.php`

### 🔹 Observer Pattern
- **Uso**: Sistema de notificações para eventos de pedidos
- **Justificativa**: Desacopla notificações do código principal, permite múltiplos observadores
- **Implementação**: `app/services/NotificationService.php`

### 🔹 Strategy Pattern
- **Uso**: Diferentes estratégias de exportação (CSV, JSON, XML)
- **Justificativa**: Permite trocar algoritmos dinamicamente, facilita adição de novos formatos
- **Implementação**: `app/services/ExportService.php`

---

## Tecnologias Utilizadas

- **Backend**: PHP 7.4+ (Orientação a Objetos)
- **Frontend**: HTML5, CSS3, JavaScript Vanilla
- **Banco de Dados**: SQLite
- **Arquitetura**: MVC + Repository Pattern
- **Pagamentos**: SDK Efí Bank
- **Servidor**: Apache/Nginx

## Observações

- Sistema totalmente funcional com todos os padrões GoF implementados
- Código limpo e bem documentado
- Interface responsiva e acessível
- Integração real com gateway de pagamento
- Maior atenção e desenvolvimento no sistema de pagamento
- Funcionalidade de mostrar/ocultar senha implementada com ícone intuitivo

---

**Desenvolvido para impulsionar restaurantes no mundo digital**

Diagrama de Classe: https://drive.google.com/file/d/1E4g3ZwAnfpeIeYeuObh-LsSk_jGQSE-X/view?usp=sharing
Diagrama de Caso de Uso: https://drive.google.com/file/d/1tnLYNR0htH2pKLFLZoCFRJSIBymBG5nv/view?usp=sharing