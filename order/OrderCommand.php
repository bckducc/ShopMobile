<?php
// order/OrderCommand.php

require_once __DIR__ . '/../db/Database.php';

interface Command {
    public function execute();
}

class OrderReceiver {
    public function createOrder($userId, $items, $total) {
        $db = Database::getInstance()->getConnection();

        // 1. Ghi đơn hàng chính vào bảng orders
        $stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
        if (!$stmt) {
            die("❌ Lỗi khi prepare orders: " . $db->error);
        }
        $stmt->bind_param("id", $userId, $total);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // 2. Ghi từng sản phẩm vào bảng order_items
        $stmtDetail = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$stmtDetail) {
            die("❌ Lỗi khi prepare order_items: " . $db->error);
        }

        foreach ($items as $item) {
            $productId = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $stmtDetail->bind_param("iiid", $orderId, $productId, $quantity, $price);
            $stmtDetail->execute();
        }

        // 3. Xác nhận đơn hàng
        echo "<h4>✔ Đơn hàng #{$orderId} đã được ghi!</h4>";
        echo "<ul>";
        foreach ($items as $item) {
            echo "<li>{$item['name']} x {$item['quantity']} - " . number_format($item['price']) . "đ</li>";
        }
        echo "</ul>";
        echo "<p><strong>Tổng tiền:</strong> " . number_format($total) . "đ</p>";
    }
}

class CreateOrderCommand implements Command {
    private $receiver;
    private $userId;
    private $items;
    private $total;

    public function __construct($receiver, $userId, $items, $total) {
        $this->receiver = $receiver;
        $this->userId = $userId;
        $this->items = $items;
        $this->total = $total;
    }

    public function execute() {
        $this->receiver->createOrder($this->userId, $this->items, $this->total);
    }
}

class OrderInvoker {
    private $command;

    public function setCommand(Command $command) {
        $this->command = $command;
    }

    public function run() {
        $this->command->execute();
    }
}
