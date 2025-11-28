<?php
/**
 * Model Restaurant
 */
class Restaurant {
    public $id;
    public $name;
    public $email;
    public $whatsapp;
    public $address;
    public $password;
    public $created_at;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->whatsapp = $data['whatsapp'] ?? '';
            $this->address = $data['address'] ?? '';
            $this->password = $data['password'] ?? '';
            $this->created_at = $data['created_at'] ?? null;
        }
    }
}
?>