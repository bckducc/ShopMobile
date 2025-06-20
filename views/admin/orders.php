<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';
require_once __DIR__ . '/../../db/Database.php';

AuthProxy::requireAdmin();

$db = Database::getInstance()->getConnection();
$result = $db->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<?php include '../header.php'; ?>

<h2 class="mb-4">📦 Danh sách đơn hàng</h2>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Mã đơn</th>
            <th>User ID</th>
            <th>Tổng tiền</th>
            <th>Thời gian</th>
            <th>Chi tiết</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= $row['user_id'] ?></td>
                <td><?= number_format($row['total_amount']) ?>đ</td>
                <td><?= $row['order_date'] ?></td>
                <td>
                    <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">
                        Chi tiết
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary mt-3">← Quay về trang quản trị</a>

<?php include '../footer.php'; ?>
