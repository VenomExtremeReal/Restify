# 🎮 Controllers - Camada de Controle

## 📋 Visão Geral

Os controllers são responsáveis por receber requisições HTTP, processar a lógica de apresentação e retornar as views apropriadas. Seguem o padrão MVC e não contêm lógica de negócio complexa.

## 📁 Estrutura

```
controllers/
├── AdminController.php         # Painel administrativo
├── AuthController.php          # Autenticação e registro
├── CartController.php          # Carrinho de compras
├── HomeController.php          # Página inicial e serviços
├── PaymentController.php       # Processamento de pagamentos
├── RestaurantController.php    # Dashboard do restaurante
└── SettingsController.php      # Configurações (tema/idioma)
```

## 🔧 Controllers Disponíveis

### 1. AdminController

**Responsabilidade**: Gerenciar painel administrativo

**Métodos**:
- `dashboard()` - Exibe estatísticas gerais
- `services()` - CRUD de serviços
- `orders()` - Listagem de pedidos
- `restaurants()` - Listagem de restaurantes
- `export()` - Exportação de relatórios

**Autenticação**: Requer `isAdmin() === true`

**Exemplo**:
```php
$controller = new AdminController();
$controller->dashboard(); // Exibe dashboard admin
```

---

### 2. AuthController

**Responsabilidade**: Autenticação e registro de usuários

**Métodos**:
- `login()` - GET: Exibe formulário | POST: Processa login
- `register()` - GET: Exibe formulário | POST: Cria conta
- `logout()` - Destroi sessão e redireciona

**Autenticação**: Não requerida (exceto logout)

**Exemplo**:
```php
$controller = new AuthController();
$controller->login(); // GET: formulário, POST: autentica
```

**Validações**:
- Email válido e único
- Senha mínima de 6 caracteres
- Campos obrigatórios preenchidos

---

### 3. CartController

**Responsabilidade**: Gerenciar carrinho de compras

**Métodos**:
- `index()` - Exibe itens do carrinho
- `add()` - Adiciona serviço ao carrinho
- `remove()` - Remove serviço do carrinho
- `checkout()` - Finaliza compra

**Autenticação**: Requerida

**Exemplo**:
```php
$controller = new CartController();
$controller->add(); // POST: service_id
```

**Sessão**:
```php
$_SESSION['cart'] = [
    'service_id' => quantity,
    // ...
];
```

---

### 4. HomeController

**Responsabilidade**: Páginas públicas

**Métodos**:
- `index()` - Página inicial
- `services()` - Catálogo de serviços

**Autenticação**: Não requerida

**Exemplo**:
```php
$controller = new HomeController();
$controller->index(); // Exibe home
```

---

### 5. PaymentController

**Responsabilidade**: Processar pagamentos

**Métodos**:
- `process()` - Cria cobrança na Efí Bank
- `success()` - Confirmação de pagamento
- `cancel()` - Cancelamento de pagamento

**Autenticação**: Requerida

**Exemplo**:
```php
$controller = new PaymentController();
$controller->process(); // POST: payment_method
```

**Fluxo**:
1. Recebe método de pagamento
2. Cria cobrança via EfiPaymentService
3. Salva pedido no banco
4. Redireciona para confirmação

---

### 6. RestaurantController

**Responsabilidade**: Dashboard do restaurante

**Métodos**:
- `dashboard()` - Visão geral do restaurante
- `orders()` - Pedidos do restaurante
- `chat()` - Chat com suporte
- `profile()` - Editar perfil

**Autenticação**: Requerida (restaurante)

**Exemplo**:
```php
$controller = new RestaurantController();
$controller->dashboard(); // Exibe dashboard
```

---

### 7. SettingsController

**Responsabilidade**: Configurações do usuário

**Métodos**:
- `changeLanguage()` - Altera idioma
- `toggleTheme()` - Alterna tema claro/escuro

**Autenticação**: Não requerida

**Exemplo**:
```php
$controller = new SettingsController();
$controller->changeLanguage(); // POST: lang=en
```

## 🎯 Padrões e Convenções

### Estrutura Básica

```php
<?php
class ExemploController {
    
    public function index() {
        // 1. Validar autenticação
        if (!isLoggedIn()) {
            redirect('/login');
        }
        
        // 2. Buscar dados (via Repository)
        $repo = new ExemploRepository();
        $data = $repo->findAll();
        
        // 3. Processar dados (via Service se necessário)
        $service = new ExemploService();
        $processedData = $service->process($data);
        
        // 4. Passar para view
        $title = 'Título da Página';
        include __DIR__ . '/../views/exemplo/index.php';
    }
    
    public function create() {
        // POST: Criar novo registro
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar dados
            $errors = $this->validate($_POST);
            
            if (empty($errors)) {
                // Salvar via Repository
                $repo = new ExemploRepository();
                $repo->save($_POST);
                
                // Redirecionar com sucesso
                $_SESSION['success'] = 'Criado com sucesso!';
                redirect('/exemplo');
            }
        }
        
        // GET: Exibir formulário
        include __DIR__ . '/../views/exemplo/create.php';
    }
}
```

### Validação de Dados

```php
private function validate($data) {
    $errors = [];
    
    if (empty($data['campo'])) {
        $errors[] = 'Campo obrigatório';
    }
    
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email inválido';
    }
    
    return $errors;
}
```

### Tratamento de Erros

```php
try {
    $result = $service->execute();
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Erro ao processar requisição';
    redirect('/error');
}
```

## 🔒 Segurança

### Proteção CSRF

```php
// Gerar token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Validar token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token inválido');
}
```

### Sanitização de Entrada

```php
$input = filter_input(INPUT_POST, 'campo', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
```

### Escape de Saída

```php
echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
```

## 📝 Boas Práticas

1. **Controllers magros**: Lógica complexa vai para Services
2. **Validação**: Sempre validar entrada do usuário
3. **Redirecionamento**: Usar `redirect()` após POST
4. **Mensagens**: Usar `$_SESSION['success']` e `$_SESSION['error']`
5. **Autenticação**: Verificar no início do método
6. **Nomenclatura**: Métodos em camelCase, classes em PascalCase

## 🧪 Testando Controllers

```php
// Simular requisição POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['campo'] = 'valor';

// Executar controller
$controller = new ExemploController();
$controller->create();

// Verificar resultado
assert($_SESSION['success'] === 'Criado com sucesso!');
```

## 📚 Referências

- [PSR-12: Coding Style](https://www.php-fig.org/psr/psr-12/)
- [MVC Pattern](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- [OWASP Security](https://owasp.org/www-project-top-ten/)
