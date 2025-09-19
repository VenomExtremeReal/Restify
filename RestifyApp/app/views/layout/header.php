<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Restify - Soluções para Restaurantes' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="<?= BASE_URL ?>/" class="logo">Restify</a>
                
                <nav class="nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a>
                            <a href="<?= BASE_URL ?>/admin/orders">Pedidos</a>
                            <a href="<?= BASE_URL ?>/admin/restaurants">Restaurantes</a>
                            <a href="<?= BASE_URL ?>/admin/services">Serviços</a>
                            <a href="<?= BASE_URL ?>/admin/chat">Chat</a>
                            <a href="<?= BASE_URL ?>/auth/logout">Sair</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/restaurant/dashboard">Dashboard</a>
                            <a href="<?= BASE_URL ?>/restaurant/orders">Meus Pedidos</a>
                            <a href="<?= BASE_URL ?>/restaurant/chat">Chat</a>
                            <a href="<?= BASE_URL ?>/cart" class="cart-icon">
                                🛒
                                <?php 
                                $cartService = new CartService();
                                $itemCount = $cartService->getItemCount();
                                ?>
                                <span class="cart-count" style="display: <?= $itemCount > 0 ? 'flex' : 'none' ?>">
                                    <?= $itemCount ?>
                                </span>
                            </a>
                            <a href="<?= BASE_URL ?>/auth/logout">Sair</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/auth/login">Login</a>
                        <a href="<?= BASE_URL ?>/auth/register">Cadastrar</a>
                        <a href="<?= BASE_URL ?>/cart" class="cart-icon">
                            🛒
                            <?php 
                            $cartService = new CartService();
                            $itemCount = $cartService->getItemCount();
                            ?>
                            <span class="cart-count" style="display: <?= $itemCount > 0 ? 'flex' : 'none' ?>">
                                <?= $itemCount ?>
                            </span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">