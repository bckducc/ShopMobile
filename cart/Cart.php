<?php
// cart/Cart.php

interface ShippingStrategy {
    public function calculateShipping($total);
}

class FlatRateShipping implements ShippingStrategy {
    public function calculateShipping($total) {
        return 30000; // Phí cố định
    }
}

class FreeShippingOver500k implements ShippingStrategy {
    public function calculateShipping($total) {
        return ($total >= 500000) ? 0 : 25000;
    }
}

// Observer
interface CartObserver {
    public function update($cart);
}

// Lớp chính Cart
class Cart {
    private $items = []; // ['id' => [...]]
    private $observers = [];
    private $shippingStrategy;

    public function addObserver(CartObserver $observer) {
        $this->observers[] = $observer;
    }

    private function notifyObservers() {
        foreach ($this->observers as $obs) {
            $obs->update($this);
        }
    }

    public function setShippingStrategy(ShippingStrategy $strategy) {
        $this->shippingStrategy = $strategy;
    }

    public function addItem($id, $name, $price, $quantity = 1) {
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
        } else {
            $this->items[$id] = [
                'id' => $id, // 👈 THÊM DÒNG NÀY
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity
            ];
        }
        $this->notifyObservers();
    }

    public function removeItem($id) {
        unset($this->items[$id]);
        $this->notifyObservers();
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getShippingFee() {
        return $this->shippingStrategy ? $this->shippingStrategy->calculateShipping($this->getTotal()) : 0;
    }

    public function getGrandTotal() {
        return $this->getTotal() + $this->getShippingFee();
    }
}

// Observer mẫu: ghi log hoặc cập nhật giao diện
class CartLogger implements CartObserver {
    public function update($cart) {
        error_log("Cart updated. Tổng tiền: " . $cart->getTotal());
    }
}
