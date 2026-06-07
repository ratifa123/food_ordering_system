<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) { die("Invalid order id"); }

// Delete order and related order_items
$stmt = $pdo->prepare("DELETE FROM orders WHERE order_id=?");
$stmt->execute([$order_id]);

header("Location: orders.php");
exit();
?>