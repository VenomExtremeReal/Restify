<?php
/**
 * Model Order
 */
class Order {
    public $id;
    public $restaurant_id;
    public $total_amount;
    public $status;
    public $payment_method;
    public $payment_id;
    public $payment_status;
    public $created_at;
    public $updated_at;
    public $items = [];
    
    // Propriedades extras para joins
    public $restaurant_name;
    public $restaurant_email;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->restaurant_id = $data['restaurant_id'] ?? null;
            $this->total_amount = $data['total_amount'] ?? 0;
            $this->status = $data['status'] ?? 'pending';
            $this->payment_method = $data['payment_method'] ?? null;
            $this->payment_id = $data['payment_id'] ?? null;
            $this->payment_status = $data['payment_status'] ?? 'pending';
            $this->created_at = $data['created_at'] ?? null;
            $this->updated_at = $data['updated_at'] ?? null;
            $this->restaurant_name = $data['restaurant_name'] ?? null;
            $this->restaurant_email = $data['restaurant_email'] ?? null;
        }
    }
}
?>