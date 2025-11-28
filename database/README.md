# 🗄️ Database - Camada de Dados

## 📋 Visão Geral

O Restify utiliza SQLite como banco de dados embutido, proporcionando simplicidade, portabilidade e zero configuração. Todos os dados são armazenados em um único arquivo `restify.db`.

## 📁 Estrutura

```
database/
├── clean_database.sql          # Script para limpar dados
├── migrate_payment_method.php  # Migration de pagamentos
├── restify.db                  # Banco SQLite (gerado automaticamente)
└── schema.sql                  # Schema completo do banco
```

## 🏗️ Schema do Banco de Dados

### Diagrama ER

```
┌─────────────────┐         ┌─────────────────┐
│   restaurants   │         │    services     │
├─────────────────┤         ├─────────────────┤
│ id (PK)         │         │ id (PK)         │
│ name            │         │ name            │
│ owner_name      │         │ description     │
│ email (UNIQUE)  │         │ price           │
│ password        │         │ type            │
│ food_type       │         │ is_active       │
│ address         │         │ created_at      │
│ phone           │         └─────────────────┘
│ created_at      │                 │
└─────────────────┘                 │
         │                          │
         │ 1                        │
         │                          │
         │ N                        │ N
         │                          │
┌─────────────────┐         ┌─────────────────┐
│     orders      │────N────│  order_items    │
├─────────────────┤         ├─────────────────┤
│ id (PK)         │         │ id (PK)         │
│ restaurant_id(FK)│        │ order_id (FK)   │
│ total           │         │ service_id (FK) │
│ status          │         │ quantity        │
│ payment_method  │         │ price           │
│ payment_id      │         └─────────────────┘
│ created_at      │
└─────────────────┘
         │
         │ 1
         │
         │ N
         │
┌─────────────────┐
│    messages     │
├─────────────────┤
│ id (PK)         │
│ restaurant_id(FK)│
│ sender          │
│ message         │
│ created_at      │
└─────────────────┘
```

## 📊 Tabelas

### 1. restaurants

Armazena dados dos restaurantes cadastrados.

```sql
CREATE TABLE restaurants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    owner_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    food_type VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

**Campos**:
- `id`: Identificador único (auto-incremento)
- `name`: Nome do restaurante
- `owner_name`: Nome do proprietário
- `email`: Email único para login
- `password`: Senha hasheada (bcrypt)
- `food_type`: Tipo de comida (Italiana, Japonesa, etc.)
- `address`: Endereço completo
- `phone`: Telefone de contato
- `created_at`: Data de cadastro

**Índices**:
```sql
CREATE UNIQUE INDEX idx_restaurants_email ON restaurants(email);
CREATE INDEX idx_restaurants_created_at ON restaurants(created_at);
```

**Exemplo de Registro**:
```sql
INSERT INTO restaurants (name, owner_name, email, password, food_type, address, phone)
VALUES (
    'Restaurante Bella Italia',
    'João Silva',
    'joao@bellaitalia.com',
    '$2y$10$abcdefghijklmnopqrstuvwxyz...',
    'Italiana',
    'Rua das Flores, 123 - Centro',
    '(31) 98765-4321'
);
```

---

### 2. services

Catálogo de serviços e pacotes disponíveis.

```sql
CREATE TABLE services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    type VARCHAR(50) DEFAULT 'service',
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

**Campos**:
- `id`: Identificador único
- `name`: Nome do serviço
- `description`: Descrição detalhada
- `price`: Preço em reais
- `type`: Tipo (`service` ou `package`)
- `is_active`: Se está ativo (1) ou inativo (0)
- `created_at`: Data de criação

**Tipos**:
- `service`: Serviço individual
- `package`: Pacote com múltiplos serviços

**Serviços Padrão**:
```sql
INSERT INTO services (name, description, price, type) VALUES
('Site com Hospedagem', 'Site profissional com hospedagem por 1 ano', 299.99, 'service'),
('Instagram + 5 Posts', 'Gerenciamento de Instagram com 5 posts mensais', 199.99, 'service'),
('Google Maps + QR Codes', 'Cadastro no Google Maps e geração de QR codes', 149.99, 'service'),
('Cardápio Online', 'Cardápio digital interativo', 99.99, 'service'),
('Pacote Básico', 'Site + Instagram', 449.99, 'package'),
('Pacote Completo', 'Todos os serviços incluídos', 649.99, 'package');
```

---

### 3. orders

Pedidos realizados pelos restaurantes.

```sql
CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    restaurant_id INTEGER NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(50),
    payment_id VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);
```

**Campos**:
- `id`: Identificador único
- `restaurant_id`: ID do restaurante (FK)
- `total`: Valor total do pedido
- `status`: Status do pedido
- `payment_method`: Método de pagamento usado
- `payment_id`: ID da transação na Efí Bank
- `created_at`: Data do pedido

**Status Possíveis**:
- `pending`: Aguardando pagamento
- `paid`: Pago
- `processing`: Em processamento
- `completed`: Concluído
- `cancelled`: Cancelado

**Métodos de Pagamento**:
- `pix`: PIX
- `credit_card`: Cartão de Crédito
- `boleto`: Boleto Bancário
- `carne`: Carnê

**Índices**:
```sql
CREATE INDEX idx_orders_restaurant_id ON orders(restaurant_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_created_at ON orders(created_at);
```

---

### 4. order_items

Itens individuais de cada pedido.

```sql
CREATE TABLE order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    quantity INTEGER DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);
```

**Campos**:
- `id`: Identificador único
- `order_id`: ID do pedido (FK)
- `service_id`: ID do serviço (FK)
- `quantity`: Quantidade contratada
- `price`: Preço unitário no momento da compra

**Índices**:
```sql
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_order_items_service_id ON order_items(service_id);
```

**Exemplo de Consulta**:
```sql
-- Buscar itens de um pedido
SELECT 
    oi.id,
    s.name AS service_name,
    oi.quantity,
    oi.price,
    (oi.quantity * oi.price) AS subtotal
FROM order_items oi
JOIN services s ON oi.service_id = s.id
WHERE oi.order_id = 1;
```

---

### 5. messages

Mensagens do sistema de chat.

```sql
CREATE TABLE messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    restaurant_id INTEGER NOT NULL,
    sender VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
);
```

**Campos**:
- `id`: Identificador único
- `restaurant_id`: ID do restaurante (FK)
- `sender`: Quem enviou (`admin` ou `restaurant`)
- `message`: Conteúdo da mensagem
- `created_at`: Data/hora do envio

**Índices**:
```sql
CREATE INDEX idx_messages_restaurant_id ON messages(restaurant_id);
CREATE INDEX idx_messages_created_at ON messages(created_at);
```

## 🔧 Operações Comuns

### Consultas Úteis

**Total de pedidos por restaurante**:
```sql
SELECT 
    r.name AS restaurant,
    COUNT(o.id) AS total_orders,
    SUM(o.total) AS total_revenue
FROM restaurants r
LEFT JOIN orders o ON r.id = o.restaurant_id
GROUP BY r.id
ORDER BY total_revenue DESC;
```

**Serviços mais vendidos**:
```sql
SELECT 
    s.name,
    SUM(oi.quantity) AS total_sold,
    SUM(oi.quantity * oi.price) AS revenue
FROM services s
JOIN order_items oi ON s.id = oi.service_id
GROUP BY s.id
ORDER BY total_sold DESC;
```

**Pedidos pendentes**:
```sql
SELECT 
    o.id,
    r.name AS restaurant,
    o.total,
    o.payment_method,
    o.created_at
FROM orders o
JOIN restaurants r ON o.restaurant_id = r.id
WHERE o.status = 'pending'
ORDER BY o.created_at DESC;
```

**Histórico de chat**:
```sql
SELECT 
    m.sender,
    m.message,
    m.created_at
FROM messages m
WHERE m.restaurant_id = 1
ORDER BY m.created_at ASC;
```

## 🛠️ Manutenção

### Backup do Banco

```bash
# Backup simples
cp database/restify.db database/backup_$(date +%Y%m%d).db

# Backup com compressão
tar -czf restify_backup_$(date +%Y%m%d).tar.gz database/restify.db
```

### Restaurar Backup

```bash
# Restaurar
cp database/backup_20250115.db database/restify.db
```

### Limpar Dados de Teste

```bash
# Executar script de limpeza
sqlite3 database/restify.db < database/clean_database.sql
```

### Verificar Integridade

```bash
sqlite3 database/restify.db "PRAGMA integrity_check;"
```

### Otimizar Banco

```bash
sqlite3 database/restify.db "VACUUM;"
```

## 📊 Estatísticas

### Tamanho do Banco

```bash
ls -lh database/restify.db
```

### Contagem de Registros

```sql
SELECT 
    'restaurants' AS table_name, COUNT(*) AS count FROM restaurants
UNION ALL
SELECT 'services', COUNT(*) FROM services
UNION ALL
SELECT 'orders', COUNT(*) FROM orders
UNION ALL
SELECT 'order_items', COUNT(*) FROM order_items
UNION ALL
SELECT 'messages', COUNT(*) FROM messages;
```

## 🔒 Segurança

### Permissões Recomendadas

```bash
# Linux/Mac
chmod 755 database/
chmod 666 database/restify.db

# Proprietário
chown www-data:www-data database/restify.db
```

### Proteção de Acesso

```apache
# .htaccess na pasta database/
<Files "*.db">
    Require all denied
</Files>
```

### Senhas

- Sempre usar `password_hash()` para armazenar senhas
- Nunca armazenar senhas em texto plano
- Usar `password_verify()` para validar

```php
// Criar hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// Verificar
if (password_verify($password, $hash)) {
    // Senha correta
}
```

## 🧪 Testes

### Popular Banco de Teste

```sql
-- Inserir restaurante de teste
INSERT INTO restaurants (name, owner_name, email, password, food_type)
VALUES ('Teste Restaurant', 'Teste User', 'teste@teste.com', 
        '$2y$10$abcd...', 'Teste');

-- Inserir pedido de teste
INSERT INTO orders (restaurant_id, total, status, payment_method)
VALUES (1, 299.99, 'paid', 'pix');

-- Inserir item de teste
INSERT INTO order_items (order_id, service_id, quantity, price)
VALUES (1, 1, 1, 299.99);
```

## 📚 Referências

- [SQLite Documentation](https://www.sqlite.org/docs.html)
- [SQLite Best Practices](https://www.sqlite.org/bestpractice.html)
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
