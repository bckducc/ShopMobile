<?php
session_start();
require_once 'auth/AuthFacade.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new AuthService();
    $success = $auth->login($_POST['username'], $_POST['password']);

    if ($success) {
        // Nếu người dùng là admin thì chuyển đến dashboard
        if ($_SESSION["user"]["role"] === "admin") {
            header("Location: views/admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="container mt-5">
    <h2>Đăng nhập</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Đăng nhập</button>
    </form>
</body>
</html>
