<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';

AuthProxy::requireAdmin();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="container mt-5">
    <h2>Chào mừng quản trị viên <?= $_SESSION["user"]["username"] ?></h2>

    <div class="mt-4">
        <ul class="list-group">
            <li class="list-group-item"><a href="..//index.php">Trang sản phẩm</a></li>
            <li class="list-group-item"><a href="products.php">Quản lý sản phẩm</a></li>
            <li class="list-group-item"><a href="orders.php">Quản lý đơn hàng</a></li>
            <li class="list-group-item"><a href="#">Quản lý người dùng (đang phát triển)</a></li>
            <li class="list-group-item"><a href="#">Thống kê doanh thu (đang phát triển)</a></li>
            <li class="list-group-item"><a href="../../logout.php" class="text-danger">Đăng xuất</a></li>
        </ul>

    </div>
</body>

</html>