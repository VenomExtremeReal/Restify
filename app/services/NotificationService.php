<?php
/**
 * Observer Pattern - Sistema de notificações
 */

interface Observer {
    public function update($event, $data);
}

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify($event, $data);
}

/**
 * Serviço de notificações - Subject
 */
class NotificationService implements Subject {
    private $observers = [];
    
    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }
    
    public function detach(Observer $observer) {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }
    
    public function notify($event, $data) {
        foreach ($this->observers as $observer) {
            $observer->update($event, $data);
        }
    }
    
    /**
     * Notificar evento de pedido
     */
    public function notifyOrderEvent($event, $orderData) {
        $this->notify($event, $orderData);
    }
}

/**
 * Observer para email
 */
class EmailNotificationObserver implements Observer {
    public function update($event, $data) {
        switch ($event) {
            case 'order_created':
                $this->sendOrderConfirmation($data);
                break;
            case 'payment_confirmed':
                $this->sendPaymentConfirmation($data);
                break;
        }
    }
    
    private function sendOrderConfirmation($orderData) {
        // Simular envio de email
        error_log("Email enviado: Pedido #{$orderData['id']} criado");
    }
    
    private function sendPaymentConfirmation($orderData) {
        // Simular envio de email
        error_log("Email enviado: Pagamento confirmado para pedido #{$orderData['id']}");
    }
}

/**
 * Observer para log
 */
class LogNotificationObserver implements Observer {
    public function update($event, $data) {
        $logMessage = date('Y-m-d H:i:s') . " - Evento: $event - Dados: " . json_encode($data);
        error_log($logMessage);
    }
}
?>