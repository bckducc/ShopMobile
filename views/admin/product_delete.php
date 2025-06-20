<?php
session_start();
require_once __DIR__ . '/../../auth/AuthProxy.php';
require_once __DIR__ . '/../../db/Database.php';

AuthProxy::requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int)$_GET['id'];
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: products.php");
exit;
