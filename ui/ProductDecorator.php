<?php
// ui/ProductDecorator.php

interface DisplayableProduct {
    public function display();
}

// Class gốc
class BasicProduct implements DisplayableProduct {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function display() {
        return $this->name;
    }
}

// Abstract Decorator
abstract class ProductDecorator implements DisplayableProduct {
    protected $product;

    public function __construct(DisplayableProduct $product) {
        $this->product = $product;
    }
}

// Decorator: Giảm giá
class SaleBadge extends ProductDecorator {
    public function display() {
        return $this->product->display() . " <span class='badge bg-danger'>Giảm giá</span>";
    }
}

// Decorator: Mới
class NewBadge extends ProductDecorator {
    public function display() {
        return $this->product->display() . " <span class='badge bg-success'>Mới</span>";
    }
}

// Decorator: Hot
class HotBadge extends ProductDecorator {
    public function display() {
        return $this->product->display() . " <span class='badge bg-warning text-dark'>Hot</span>";
    }
}
