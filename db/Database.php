<?php
// db/Database.php

class Database {
    private static $instance = null;
    private $connection;

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "banhang"; // tên database cần tạo

    // Constructor private không cho khởi tạo từ bên ngoài
    private function __construct() {
        $this->connection = new mysqli(
            $this->host, 
            $this->username, 
            $this->password, 
            $this->database
        );

        if ($this->connection->connect_error) {
            die("Kết nối thất bại: " . $this->connection->connect_error);
        }
    }

    // Trả về instance duy nhất
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Trả về kết nối để dùng ở nơi khác
    public function getConnection() {
        return $this->connection;
    }
}
