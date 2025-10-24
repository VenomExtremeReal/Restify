<?php
/**
 * Model Order
 */
class Order {
    public $id;
    public $restaurant_id;
    public $total_amount;
    public $status;
    public $created_at;
    public $items = [];

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->restaurant_id = $data['restaurant_id'] ?? null;
            $this->total_amount = $data['total_amount'] ?? 0;
            $this->status = $data['status'] ?? 'pending';
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
?>