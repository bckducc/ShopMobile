<?php
session_start();
require_once 'auth/AuthProxy.php';
require_once 'db/Database.php';
require_once 'products/ProductFactory.php';
require_once 'ui/ProductDecorator.php';

AuthProxy::requireLogin();

$db = Database::getInstance()->getConnection();
$result = $db->query("
    SELECT p.*, c.name AS category
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
");
?>

<?php include 'views/header.php'; ?>

<h2 class="mb-4 text-center">🛍️ Danh sách sản phẩm</h2>

<!-- Thông báo -->
<div id="alert-container" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:9999;"></div>

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
                <h5 class="card-title"><?= $row['name'] ?></h5>
                <ul class="list-unstyled small text-muted mb-2">
                    <li><strong>Mã SP:</strong> <?= $row['id'] ?></li>
                    <li><strong>Loại:</strong> <?= $row['category'] ?></li>
                    <?php if (!empty($row['description'])): ?>
                        <li><strong>Mô tả:</strong> <?= htmlspecialchars($row['description']) ?></li>
                    <?php endif; ?>
                </ul>

                <p class="card-text"><?= $display->display() ?></p>

                <!-- Nút thêm vào giỏ -->
                <form method="GET" action="views/cart.php" target="hidden_cart" onsubmit="showAddedMessage()">
                    <input type="hidden" name="add" value="1">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']) ?>">
                    <input type="hidden" name="price" value="<?= $row['price'] ?>">
                    <button type="submit" class="btn btn-success btn-sm">➕ Thêm vào giỏ</button>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<iframe name="hidden_cart" style="display:none;"></iframe>

<script>
function showAddedMessage() {
    const container = document.getElementById('alert-container');
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show';
    alert.role = 'alert';
    alert.innerHTML = '✔ Sản phẩm đã được thêm vào giỏ hàng!'
        + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    container.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}
</script>

<?php include 'views/footer.php'; ?>
