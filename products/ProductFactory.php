<?php
// products/ProductFactory.php

interface ProductInterface {
    public function getInfo();
}

class ShirtProduct implements ProductInterface {
    private $name, $price;
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }
    public function getInfo() {
        return "Áo: {$this->name} - Giá: {$this->price}đ";
    }
}

class PantsProduct implements ProductInterface {
    private $name, $price;
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }
    public function getInfo() {
        return "Quần: {$this->name} - Giá: {$this->price}đ";
    }
}

class AccessoryProduct implements ProductInterface {
    private $name, $price;
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }
    public function getInfo() {
        return "Phụ kiện: {$this->name} - Giá: {$this->price}đ";
    }
}

class ProductFactory {
    public static function createProduct($type, $name, $price) {
        switch (strtolower($type)) {
            case 'áo':
                return new ShirtProduct($name, $price);
            case 'quần':
                return new PantsProduct($name, $price);
            case 'phụ kiện':
                return new AccessoryProduct($name, $price);
            default:
                return new class($name, $price) implements ProductInterface {
                    private $name, $price;
                    public function __construct($name, $price) {
                        $this->name = $name;
                        $this->price = $price;
                    }
                    public function getInfo() {
                        return "Sản phẩm khác: {$this->name} - Giá: {$this->price}đ";
                    }
                };
        }
    }
}
