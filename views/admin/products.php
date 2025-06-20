<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';
require_once __DIR__ . '/../../db/Database.php';

AuthProxy::requireAdmin();

$db = Database::getInstance()->getConnection();
$sql = "SELECT p.*, c.name AS category
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id";
$result = $db->query($sql);
?>

<?php include '../header.php'; ?>

<h2 class="mb-4">📦 Quản lý sản phẩm</h2>

<a href="product_form.php" class="btn btn-primary mb-3">➕ Thêm sản phẩm</a>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Mã</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Loại</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($p = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['name'] ?></td>
            <td><?= number_format($p['price']) ?>đ</td>
            <td><?= $p['category'] ?></td>
            <td>
                <a href="product_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">✏️ Sửa</a>
                <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa sản phẩm này?')">🗑️ Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard.php" class="btn btn-secondary mt-3">← Quay về trang quản trị</a>

<?php include '../footer.php'; ?>
