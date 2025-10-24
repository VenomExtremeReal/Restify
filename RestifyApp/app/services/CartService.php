<?php
/**
 * Service para carrinho de compras
 */
class CartService {
    
    /**
     * Adicionar item ao carrinho
     */
    public function addItem($serviceId, $quantity = 1) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$serviceId])) {
            $_SESSION['cart'][$serviceId] += $quantity;
        } else {
            $_SESSION['cart'][$serviceId] = $quantity;
        }
    }

    /**
     * Remover item do carrinho
     */
    public function removeItem($serviceId) {
        if (isset($_SESSION['cart'][$serviceId])) {
            unset($_SESSION['cart'][$serviceId]);
        }
    }

    /**
     * Obter itens do carrinho
     */
    public function getItems() {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Limpar carrinho
     */
    public function clear() {
        unset($_SESSION['cart']);
    }

    /**
     * Calcular total do carrinho
     */
    public function getTotal() {
        $serviceRepo = new ServiceRepository();
        $total = 0;
        
        foreach ($this->getItems() as $serviceId => $quantity) {
            $service = $serviceRepo->findById($serviceId);
            if ($service) {
                $total += $service->price * $quantity;
            }
        }
        
        return $total;
    }

    /**
     * Contar itens no carrinho
     */
    public function getItemCount() {
        return array_sum($this->getItems());
    }
}
?>