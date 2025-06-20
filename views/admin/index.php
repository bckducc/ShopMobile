<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';
require_once __DIR__ . '/../../db/Database.php';
require_once __DIR__ . '/../../products/ProductFactory.php';
require_once __DIR__ . '/../../ui/ProductDecorator.php';

AuthProxy::requireAdmin();

$db = Database::getInstance()->getConnection();
$result = $db->query("
    SELECT p.*, c.name AS category
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
");
?>

<?php include __DIR__ . '/../header.php'; ?>

<h2 class="mb-4 text-center">📦 Quản lý sản phẩm</h2>

<div class="row">
    <?php while ($row = $result->fetch_assoc()):
        $product = ProductFactory::createProduct($row['category'], $row['name'], $row['price']);
        $display = new BasicProduct($product->getInfo());

        if ($row['price'] < 300000) $display = new SaleBadge($display);
        if ($row['id'] > 15) $display = new NewBadge($display);
        if (str_contains(strtolower($row['name']), 'giày')) $display = new HotBadge($display);
    ?>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100 border rounded-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>

                <ul class="list-unstyled small text-muted mb-2">
                    <li><strong>Mã SP:</strong> <?= $row['id'] ?></li>
                    <li><strong>Loại:</strong> <?= htmlspecialchars($row['category']) ?></li>
                    <?php if (!empty($row['description'])): ?>
                        <li><strong>Mô tả:</strong> <?= htmlspecialchars($row['description']) ?></li>
                    <?php endif; ?>
                </ul>

                <p class="card-text"><?= $display->display() ?></p>
                <!-- KHÔNG có nút thêm giỏ hàng -->
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>