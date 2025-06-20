<?php
require_once __DIR__ . '/../cart/Cart.php';
require_once __DIR__ . '/../order/OrderCommand.php';
session_start();

if (!isset($_SESSION['cart'])) {
    echo "Không có giỏ hàng để đặt.";
    exit;
}

$cart = $_SESSION['cart'];
$user = $_SESSION['user'] ?? ['id' => 0];

$receiver = new OrderReceiver();
$command = new CreateOrderCommand(
    $receiver,
    $user['id'],
    $cart->getItems(),
    $cart->getGrandTotal()
);

$invoker = new OrderInvoker();
$invoker->setCommand($command);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="container mt-5">
    <h3>Đơn hàng đã được đặt thành công!</h3>
    <?php $invoker->run(); ?>

    <a href="../index.php" class="btn btn-primary mt-4">← Quay về trang chủ</a>
</body>
</html>

<?php
// Reset giỏ sau khi hiển thị (sau HTML để không ảnh hưởng xuất PDF)
unset($_SESSION['cart']);
?>
