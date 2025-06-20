<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$isAdmin = isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === "admin";
$isAdminPage = str_contains($_SERVER['PHP_SELF'], '/admin/');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang bán hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Wrapper layout dùng Flexbox để sticky footer -->
<div class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="<?= $isAdmin && $isAdminPage ? '/views/admin/dashboard.php' : '/banhang_framework/index.php' ?>">
        🛒 TechZone
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION["user"])): ?>
                <?php if ($isAdmin): ?>
                    <?php if ($isAdminPage): ?>
                        <li class="nav-item"><a class="nav-link" href="/views/admin/products.php">Quản lý sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="/views/admin/orders.php">Quản lý đơn hàng</a></li>
                        <li class="nav-item"><a class="nav-link" href="/views/admin/dashboard.php">Quản trị</a></li>
                    <?php else: ?>
                        
                    <?php endif; ?>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="/views/cart.php">Giỏ hàng</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="/logout.php">Đăng xuất</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="/login.php">Đăng nhập</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Nội dung chính bắt đầu -->
<main class="container my-4 flex-grow-1">
