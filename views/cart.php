<?php
require_once __DIR__ . '/../cart/Cart.php'; // LUÔN load class trước session_start()
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
    $_SESSION['cart']->setShippingStrategy(new FreeShippingOver500k());
    $_SESSION['cart']->addObserver(new CartLogger());
}

$cart = $_SESSION['cart'];

if (isset($_GET['add'])) {
    $cart->addItem($_GET['id'], $_GET['name'], $_GET['price']);
}

if (isset($_GET['remove'])) {
    $cart->removeItem($_GET['id']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="container mt-5">
    <h2>Giỏ hàng của bạn</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart->getItems() as $id => $item): ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= number_format($item['price']) ?>đ</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                <td>
                    <a href="?remove=1&id=<?= $id ?>" class="btn btn-sm btn-danger">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Tổng tiền:</strong> <?= number_format($cart->getTotal()) ?>đ</p>
    <p><strong>Phí ship:</strong> <?= number_format($cart->getShippingFee()) ?>đ</p>
    <p><strong>Thanh toán:</strong> <?= number_format($cart->getGrandTotal()) ?>đ</p>

    <a href="../index.php" class="btn btn-secondary">← Tiếp tục mua</a>
    <a href="order.php" class="btn btn-success">🛒 Xác nhận đặt hàng</a>
</body>
</html>
