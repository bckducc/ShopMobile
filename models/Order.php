<?php
// models/Order.php

require_once __DIR__ . '/../db/Database.php';

class Order {
    private $id;
    private $user_id;
    private $total;
    private $created_at;

    public function __construct($id, $user_id, $total, $created_at) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->total = $total;
        $this->created_at = $created_at;
    }

    public function getSummary() {
        return "Đơn #{$this->id} | User: {$this->user_id} | Tổng: " . number_format($this->total) . "đ | Lúc: {$this->created_at}";
    }
}

class OrderCollection implements Iterator {
    private $orders = [];
    private $position = 0;

    public function __construct() {
        $this->position = 0;
        $db = Database::getInstance()->getConnection();
        $result = $db->query("SELECT * FROM orders ORDER BY created_at DESC");

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->orders[] = new Order(
                    $row['id'],
                    $row['user_id'],
                    $row['total'],
                    $row['created_at']
                );
            }
        }
    }

    public function current(): mixed {
        return $this->orders[$this->position];
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
        return isset($this->orders[$this->position]);
    }
}
