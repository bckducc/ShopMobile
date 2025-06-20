<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';
require_once __DIR__ . '/../../db/Database.php';

AuthProxy::requireAdmin();

$db = Database::getInstance()->getConnection();
$editing = false;
$product = ['id' => '', 'name' => '', 'price' => '', 'category_id' => ''];

if (isset($_GET['id'])) {
    $editing = true;
    $id = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();
}

// Lấy danh sách danh mục
$categories = $db->query("SELECT id, name FROM categories")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];

    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("UPDATE products SET name=?, price=?, category_id=? WHERE id=?");
        $stmt->bind_param("sdii", $name, $price, $category_id, $id);
    } else {
        $stmt = $db->prepare("INSERT INTO products (name, price, category_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $name, $price, $category_id);
    }

    $stmt->execute();
    header("Location: products.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $editing ? 'Sửa' : 'Thêm' ?> sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3><?= $editing ? '✏️ Sửa sản phẩm' : '➕ Thêm sản phẩm' ?></h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" step="1000" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Loại</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $product['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-success"><?= $editing ? 'Lưu thay đổi' : 'Thêm sản phẩm' ?></button>
        <a href="products.php" class="btn btn-secondary">Hủy</a>
    </form>
</body>
</html>
