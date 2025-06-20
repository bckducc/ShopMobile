<?php
// auth/AuthProxy.php

class AuthProxy {
    public static function requireLogin() {
        if (!isset($_SESSION["user"])) {
            header("Location: login.php");
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin(); // đảm bảo đã login trước

        if ($_SESSION["user"]["role"] !== "admin") {
            echo "<h3 style='color:red'>Bạn không có quyền truy cập trang này.</h3>";
            exit;
        }
    }
}
