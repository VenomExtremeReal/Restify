<?php
/**
 * Model Service
 */
class Service {
    public $id;
    public $name;
    public $description;
    public $price;
    public $type;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->description = $data['description'] ?? '';
            $this->price = $data['price'] ?? 0;
            $this->type = $data['type'] ?? 'individual';
        }
    }
}
?>