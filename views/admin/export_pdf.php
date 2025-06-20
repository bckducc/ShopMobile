<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../db/Database.php';
session_start();

if (!isset($_GET['id'])) {
    die("Thiếu ID đơn hàng");
}

$orderId = (int) $_GET['id'];
$db = Database::getInstance()->getConnection();

// Lấy thông tin đơn hàng
$order = $db->query("SELECT * FROM orders WHERE id = $orderId")->fetch_assoc();
if (!$order) die("Không tìm thấy đơn hàng.");

// JOIN để lấy tên sản phẩm
$sql = "SELECT oi.*, p.name AS product_name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = $orderId";
$details = $db->query($sql);

// Tạo PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// Nội dung HTML
$html = "<h2>Hóa đơn đơn hàng #{$order['id']}</h2>";
$html .= "<p><strong>User ID:</strong> {$order['user_id']}</p>";
$html .= "<p><strong>Ngày:</strong> {$order['created_at']}</p>";
$html .= "<hr>";
$html .= "<table border='1' cellpadding='4'>
    <thead>
        <tr>
            <th><strong>Sản phẩm</strong></th>
            <th><strong>Đơn giá</strong></th>
            <th><strong>Số lượng</strong></th>
            <th><strong>Thành tiền</strong></th>
        </tr>
    </thead><tbody>";

$total = 0;
while ($row = $details->fetch_assoc()) {
    $line = $row['price'] * $row['quantity'];
    $total += $line;
    $productName = htmlspecialchars($row['product_name']);
    $html .= "<tr>
        <td>{$productName}</td>
        <td>" . number_format($row['price']) . "đ</td>
        <td>{$row['quantity']}</td>
        <td>" . number_format($line) . "đ</td>
    </tr>";
}
$html .= "</tbody></table>";
$html .= "<h4 class='mt-3'>Tổng cộng: " . number_format($total) . "đ</h4>";

// Xuất PDF
$pdf->writeHTML($html);
$pdf->Output("hoadon_donhang_{$order['id']}.pdf", 'I');
