-- Schema SQLite do banco de dados Restify

-- Tabela de restaurantes
CREATE TABLE IF NOT EXISTS restaurants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    whatsapp TEXT NOT NULL,
    address TEXT NOT NULL,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de serviços disponíveis
CREATE TABLE IF NOT EXISTS services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL,
    type TEXT DEFAULT 'individual' CHECK(type IN ('individual', 'package'))
);

-- Tabela de pedidos
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    restaurant_id INTEGER NOT NULL,
    total_amount REAL NOT NULL,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'processing', 'completed', 'cancelled')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- Tabela de itens do pedido
CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    quantity INTEGER DEFAULT 1,
    price REAL NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Tabela de mensagens do chat
CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    restaurant_id INTEGER NOT NULL,
    sender_type TEXT NOT NULL CHECK(sender_type IN ('restaurant', 'admin')),
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- Inserir serviços padrão (apenas se não existirem)
INSERT OR IGNORE INTO services (id, name, description, price, type) VALUES
(1, 'Site com Hospedagem', 'Criação de site profissional com hospedagem incluída', 299.99, 'individual'),
(2, 'Instagram + 5 Posts', 'Criação e configuração do Instagram com 5 posts profissionais', 199.99, 'individual'),
(3, 'Google Maps + QR Codes', 'Registro no Google Maps com 10 QR codes para feedback', 149.99, 'individual'),
(4, 'Cardápio Online', 'Sistema de cardápio digital responsivo', 99.99, 'individual'),
(5, 'Pacote Básico', 'Site + Instagram (Economia de R$ 50)', 449.99, 'package'),
(6, 'Pacote Completo', 'Todos os serviços incluídos (Economia de R$ 100)', 649.99, 'package');