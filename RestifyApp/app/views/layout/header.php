<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Restify - Soluções para Restaurantes' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
        window.translations = {
            'item_added_to_cart': '<?= t('item_added_to_cart_success') ?>',
            'error_adding_item': '<?= t('error_adding_item_to_cart') ?>',
            'fill_required_fields': '<?= t('fill_all_required_fields') ?>',
            'error_sending_message': '<?= t('error_sending_message_chat') ?>',
            'processing_order': '<?= t('processing_order_wait') ?>',
            'confirm_action': '<?= t('confirm_action') ?>'
        };
    </script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="<?= BASE_URL ?>/" class="logo">Restify</a>
                
                <nav class="nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <a href="<?= BASE_URL ?>/admin/dashboard"><?= t('dashboard') ?></a>
                            <a href="<?= BASE_URL ?>/admin/orders"><?= t('orders') ?></a>
                            <a href="<?= BASE_URL ?>/admin/restaurants"><?= t('restaurants') ?></a>
                            <a href="<?= BASE_URL ?>/admin/services"><?= t('services') ?></a>
                            <a href="<?= BASE_URL ?>/admin/chat"><?= t('chat') ?></a>
                            <a href="<?= BASE_URL ?>/auth/logout"><?= t('logout') ?></a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/restaurant/dashboard"><?= t('dashboard') ?></a>
                            <a href="<?= BASE_URL ?>/restaurant/orders"><?= t('my_orders') ?></a>
                            <a href="<?= BASE_URL ?>/restaurant/chat"><?= t('chat') ?></a>
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
                            <a href="<?= BASE_URL ?>/auth/logout"><?= t('logout') ?></a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/auth/login"><?= t('login') ?></a>
                        <a href="<?= BASE_URL ?>/auth/register"><?= t('register') ?></a>
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

    <?php include '../app/views/layout/settings-panel.php'; ?>
    
    <main class="main">
        <div class="container">