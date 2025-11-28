# ⚙️ Services - Camada de Lógica de Negócio

## 📋 Visão Geral

A camada de Services contém toda a lógica de negócio da aplicação. Services são responsáveis por orquestrar operações complexas, aplicar regras de negócio e coordenar múltiplos repositories.

## 📁 Estrutura

```
services/
├── AuthService.php              # Autenticação e sessões
├── CartService.php              # Lógica do carrinho
├── EfiPaymentService.php        # Integração Efí Bank
├── ExportService.php            # Exportação de dados
├── NotificationService.php      # Sistema de notificações
└── PaymentServiceFactory.php    # Factory de pagamentos
```

## 🔧 Services Disponíveis

### 1. AuthService

**Responsabilidade**: Gerenciar autenticação e sessões

**Métodos**:

```php
class AuthService {
    // Autentica usuário
    public function login($email, $password): bool
    
    // Registra novo restaurante
    public function register($data): bool
    
    // Verifica se está logado
    public function isAuthenticated(): bool
    
    // Retorna usuário atual
    public function getCurrentUser(): ?Restaurant
    
    // Faz logout
    public function logout(): void
    
    // Verifica se é admin
    public function isAdmin(): bool
}
```

**Exemplo de Uso**:
```php
$authService = new AuthService();

// Login
if ($authService->login('email@example.com', 'senha123')) {
    echo "Login bem-sucedido!";
}

// Registro
$data = [
    'name' => 'Restaurante Exemplo',
    'owner_name' => 'João Silva',
    'email' => 'joao@exemplo.com',
    'password' => 'senha123',
    'food_type' => 'Italiana',
    'address' => 'Rua Exemplo, 123'
];

if ($authService->register($data)) {
    echo "Cadastro realizado!";
}
```

**Segurança**:
- Senhas hasheadas com `password_hash()`
- Validação de email único
- Proteção contra SQL injection via PDO

---

### 2. CartService

**Responsabilidade**: Gerenciar carrinho de compras na sessão

**Métodos**:

```php
class CartService {
    // Adiciona item ao carrinho
    public function addItem($serviceId, $quantity = 1): void
    
    // Remove item do carrinho
    public function removeItem($serviceId): void
    
    // Retorna todos os itens
    public function getItems(): array
    
    // Calcula total do carrinho
    public function getTotal(): float
    
    // Limpa carrinho
    public function clear(): void
    
    // Conta itens
    public function getItemCount(): int
}
```

**Exemplo de Uso**:
```php
$cartService = new CartService();

// Adicionar serviço
$cartService->addItem(1, 2); // service_id=1, quantity=2

// Ver total
$total = $cartService->getTotal(); // R$ 599,98

// Remover item
$cartService->removeItem(1);

// Limpar carrinho
$cartService->clear();
```

**Estrutura da Sessão**:
```php
$_SESSION['cart'] = [
    1 => 2,  // service_id => quantity
    3 => 1,
    5 => 1
];
```

---

### 3. EfiPaymentService

**Responsabilidade**: Integração com API Efí Bank

**Métodos**:

```php
class EfiPaymentService {
    // Cria cobrança PIX
    public function createPixCharge($amount, $description): array
    
    // Cria cobrança com cartão
    public function createCreditCardCharge($amount, $cardData, $installments): array
    
    // Cria boleto
    public function createBoletoCharge($amount, $customer): array
    
    // Cria carnê
    public function createCarneCharge($amount, $installments, $customer): array
    
    // Consulta status de cobrança
    public function getChargeStatus($chargeId): string
    
    // Cancela cobrança
    public function cancelCharge($chargeId): bool
}
```

**Exemplo de Uso - PIX**:
```php
$efiService = new EfiPaymentService();

$response = $efiService->createPixCharge(299.99, 'Site com Hospedagem');

// Retorno:
[
    'txid' => 'abc123...',
    'qrcode' => 'data:image/png;base64,...',
    'qrcode_text' => '00020126580014br.gov.bcb.pix...',
    'status' => 'pending'
]
```

**Exemplo de Uso - Cartão**:
```php
$cardData = [
    'number' => '4012001037141112',
    'cvv' => '123',
    'expiration_month' => '12',
    'expiration_year' => '2030',
    'holder_name' => 'JOAO SILVA'
];

$response = $efiService->createCreditCardCharge(299.99, $cardData, 3);

// Retorno:
[
    'charge_id' => 12345,
    'status' => 'approved',
    'installments' => 3,
    'installment_value' => 99.99
]
```

**Configuração**:
```php
// config/efi_credentials.php
return [
    'client_id' => 'Client_Id_...',
    'client_secret' => 'Client_Secret_...',
    'sandbox' => true,
    'certificate' => __DIR__ . '/certificates/homologacao.p12'
];
```

**Webhook**:
```php
// public/webhook/payment.php
$notification = json_decode(file_get_contents('php://input'), true);

if ($notification['evento'] === 'pix_recebido') {
    $txid = $notification['pix'][0]['txid'];
    // Atualizar status do pedido
}
```

---

### 4. ExportService (Strategy Pattern)

**Responsabilidade**: Exportar dados em múltiplos formatos

**Métodos**:

```php
class ExportService {
    // Exporta para CSV
    public function exportToCsv($data, $filename): void
    
    // Exporta para JSON
    public function exportToJson($data, $filename): void
    
    // Exporta para XML
    public function exportToXml($data, $filename): void
    
    // Exporta usando estratégia
    public function export($data, $format, $filename): void
}
```

**Exemplo de Uso**:
```php
$exportService = new ExportService();

// Buscar dados
$orderRepo = new OrderRepository();
$orders = $orderRepo->findAll();

// Exportar CSV
$exportService->exportToCsv($orders, 'pedidos.csv');

// Exportar JSON
$exportService->exportToJson($orders, 'pedidos.json');

// Exportar XML
$exportService->exportToXml($orders, 'pedidos.xml');
```

**Formato CSV**:
```csv
ID,Restaurante,Total,Status,Data
1,Restaurante A,299.99,paid,2025-01-15
2,Restaurante B,449.99,pending,2025-01-16
```

**Formato JSON**:
```json
[
    {
        "id": 1,
        "restaurant": "Restaurante A",
        "total": 299.99,
        "status": "paid",
        "date": "2025-01-15"
    }
]
```

---

### 5. NotificationService (Observer Pattern)

**Responsabilidade**: Sistema de notificações por eventos

**Métodos**:

```php
class NotificationService {
    // Registra observador
    public function attach($observer): void
    
    // Remove observador
    public function detach($observer): void
    
    // Notifica todos os observadores
    public function notify($event, $data): void
    
    // Envia email
    public function sendEmail($to, $subject, $message): bool
    
    // Envia notificação push
    public function sendPush($userId, $message): bool
}
```

**Exemplo de Uso**:
```php
$notificationService = new NotificationService();

// Registrar observadores
$notificationService->attach(new EmailObserver());
$notificationService->attach(new PushObserver());

// Disparar evento
$notificationService->notify('order_created', [
    'order_id' => 123,
    'restaurant_id' => 45,
    'total' => 299.99
]);

// Todos os observadores serão notificados
```

**Eventos Disponíveis**:
- `order_created` - Pedido criado
- `order_paid` - Pagamento confirmado
- `order_cancelled` - Pedido cancelado
- `message_received` - Nova mensagem no chat

---

### 6. PaymentServiceFactory (Factory Pattern)

**Responsabilidade**: Criar instâncias de serviços de pagamento

**Métodos**:

```php
class PaymentServiceFactory {
    // Cria serviço baseado no tipo
    public static function create($type): PaymentServiceInterface
    
    // Retorna tipos disponíveis
    public static function getAvailableTypes(): array
}
```

**Exemplo de Uso**:
```php
// Criar serviço dinamicamente
$paymentType = $_POST['payment_method']; // 'pix', 'credit_card', etc.

$paymentService = PaymentServiceFactory::create($paymentType);
$response = $paymentService->processPayment($amount, $data);
```

**Implementação**:
```php
class PaymentServiceFactory {
    public static function create($type) {
        switch($type) {
            case 'pix':
                return new PixPaymentService();
            case 'credit_card':
                return new CreditCardPaymentService();
            case 'boleto':
                return new BoletoPaymentService();
            case 'carne':
                return new CarnePaymentService();
            default:
                throw new Exception("Tipo de pagamento inválido: $type");
        }
    }
}
```

## 🎯 Padrões e Convenções

### Estrutura Básica de um Service

```php
<?php
class ExemploService {
    
    private $repository;
    
    public function __construct() {
        $this->repository = new ExemploRepository();
    }
    
    /**
     * Método público que implementa regra de negócio
     */
    public function executarOperacao($data) {
        // 1. Validar dados
        $this->validate($data);
        
        // 2. Aplicar regras de negócio
        $processedData = $this->applyBusinessRules($data);
        
        // 3. Persistir via repository
        $result = $this->repository->save($processedData);
        
        // 4. Notificar observadores (se necessário)
        $this->notifyObservers('operacao_executada', $result);
        
        return $result;
    }
    
    /**
     * Método privado de validação
     */
    private function validate($data) {
        if (empty($data['campo_obrigatorio'])) {
            throw new Exception('Campo obrigatório não preenchido');
        }
    }
    
    /**
     * Método privado com lógica de negócio
     */
    private function applyBusinessRules($data) {
        // Aplicar transformações, cálculos, etc.
        return $data;
    }
}
```

### Tratamento de Erros

```php
try {
    $service = new ExemploService();
    $result = $service->executarOperacao($data);
} catch (ValidationException $e) {
    // Erro de validação
    $_SESSION['error'] = $e->getMessage();
} catch (DatabaseException $e) {
    // Erro de banco
    error_log($e->getMessage());
    $_SESSION['error'] = 'Erro ao processar operação';
} catch (Exception $e) {
    // Erro genérico
    error_log($e->getMessage());
    $_SESSION['error'] = 'Erro inesperado';
}
```

### Injeção de Dependências

```php
class ExemploService {
    private $repository;
    private $notificationService;
    
    public function __construct(
        ExemploRepository $repository = null,
        NotificationService $notificationService = null
    ) {
        $this->repository = $repository ?? new ExemploRepository();
        $this->notificationService = $notificationService ?? new NotificationService();
    }
}
```

## 🧪 Testando Services

```php
// Mock do repository
class MockRepository {
    public function save($data) {
        return ['id' => 1, ...$data];
    }
}

// Testar service
$mockRepo = new MockRepository();
$service = new ExemploService($mockRepo);

$result = $service->executarOperacao(['campo' => 'valor']);

assert($result['id'] === 1);
assert($result['campo'] === 'valor');
```

## 📝 Boas Práticas

1. **Single Responsibility**: Cada service tem uma responsabilidade clara
2. **Dependency Injection**: Injetar dependências via construtor
3. **Validação**: Sempre validar dados de entrada
4. **Exceções**: Lançar exceções específicas para erros
5. **Logging**: Registrar operações importantes
6. **Transações**: Usar transações para operações críticas
7. **Testes**: Services devem ser facilmente testáveis

## 🔒 Segurança

### Validação de Entrada

```php
private function validateInput($data) {
    $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
    $data['amount'] = filter_var($data['amount'], FILTER_VALIDATE_FLOAT);
    
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new ValidationException('Email inválido');
    }
    
    return $data;
}
```

### Proteção de Dados Sensíveis

```php
// Nunca logar dados sensíveis
error_log("Processando pagamento para pedido: " . $orderId);
// NÃO: error_log("Cartão: " . $cardNumber);

// Mascarar dados sensíveis
$maskedCard = substr($cardNumber, 0, 4) . '********' . substr($cardNumber, -4);
```

## 📚 Referências

- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Design Patterns](https://refactoring.guru/design-patterns)
- [Clean Code](https://www.amazon.com/Clean-Code-Handbook-Software-Craftsmanship/dp/0132350882)
