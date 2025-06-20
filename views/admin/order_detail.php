<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';
require_once __DIR__ . '/../../db/Database.php';

AuthProxy::requireAdmin();

if (!isset($_GET['id'])) {
    echo "Thiếu ID đơn hàng.";
    exit;
}

$orderId = (int) $_GET['id'];
$db = Database::getInstance()->getConnection();

// Lấy đơn hàng
$order = $db->query("SELECT * FROM orders WHERE id = $orderId")->fetch_assoc();
if (!$order) {
    echo "Đơn hàng không tồn tại.";
    exit;
}

// Lấy chi tiết đơn hàng (có JOIN để lấy tên sản phẩm)
$details = $db->query("
    SELECT oi.*, p.name AS product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $orderId
");
?>

<?php include '../header.php'; ?>

<h3 class="mb-3">Chi tiết đơn hàng #<?= $orderId ?></h3>
<div class="mb-3">
    <p><strong>User ID:</strong> <?= $order['user_id'] ?></p>
    <p><strong>Tổng tiền:</strong> <?= number_format($order['total_amount']) ?>đ</p>
    <p><strong>Thời gian:</strong> <?= $order['created_at'] ?></p>
    <a href="export_pdf.php?id=<?= $orderId ?>" class="btn btn-danger">🧾 Xuất PDF</a>
</div>

<hr>

<h5 class="mt-4">Sản phẩm đã đặt:</h5>
<table class="table table-sm table-bordered mt-2">
    <thead class="table-light">
        <tr>
            <th>Tên sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $details->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= number_format($row['price']) ?>đ</td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['price'] * $row['quantity']) ?>đ</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="orders.php" class="btn btn-secondary mt-4">← Quay về danh sách đơn</a>

<?php include '../footer.php'; ?>
