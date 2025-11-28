<?php
/**
 * Model Message
 */
class Message {
    public $id;
    public $restaurant_id;
    public $sender_type;
    public $message;
    public $created_at;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->restaurant_id = $data['restaurant_id'] ?? null;
            $this->sender_type = $data['sender_type'] ?? 'restaurant';
            $this->message = $data['message'] ?? '';
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
?>