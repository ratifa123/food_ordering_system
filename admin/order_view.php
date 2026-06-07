<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
    die('<div class="alert alert-danger m-4">Invalid order ID.</div>');
}

// Fetch order info with customer details
$stmt = $pdo->prepare("SELECT o.*, u.username, u.phone, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('<div class="alert alert-danger m-4">Order not found.</div>');
}

// Fetch order items (join with food_items)
$stmt_items = $pdo->prepare("SELECT oi.*, f.name AS food_name FROM order_items oi 
    LEFT JOIN food_items f ON oi.food_id = f.food_id WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

function statusBadge($status) {
    $status = strtolower($status);
    $class = "badge ";
    if ($status === "pending") $class .= "bg-warning text-dark";
    elseif ($status === "inprogress") $class .= "bg-info text-dark";
    elseif ($status === "delivered") $class .= "bg-success";
    elseif ($status === "cancelled") $class .= "bg-danger";
    else $class .= "bg-secondary";
    return '<span class="'.$class.'">'.ucfirst($status).'</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f7fafb; }
        .order-card {
            background: #fff; border-radius: 18px;
            box-shadow: 0 6px 32px 0 rgba(25,164,99,0.13), 0 1.5px 6px 0 rgba(0,0,0,0.05);
            padding: 2.4rem 2.2rem 2rem 2.2rem;
            max-width: 720px; margin: 36px auto;
        }
        .order-title { color: #14834e; font-weight: 700; font-size: 2rem; }
        .order-number { color: #19a463; font-size: 1.16rem; font-weight: 600; }
        .section-title { font-weight: 600; color: #14834e; margin-top: 1.5rem; margin-bottom: .7rem;}
        .order-table th { background: #eafcf3; color: #19a463; }
        .order-table td, .order-table th { vertical-align: middle; }
        .order-table { font-size: 1rem; }
        .info-label { color: #888; font-size: .98rem; }
        .info-value { font-weight: 500; font-size: 1.08rem; }
        .back-link { text-decoration: none; color: #19a463; font-weight: 500; }
        .back-link:hover { text-decoration: underline; color: #14834e; }
        @media (max-width: 700px) {
            .order-card { padding: 1.3rem .5rem 1.2rem .5rem; }
            .order-title { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
<div class="order-card">
    <div class="mb-2 d-flex justify-content-between align-items-center">
        <a href="orders.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        <a href="dashboard.php" class="btn btn-outline-success btn-sm">
            <i class="bi bi-house"></i> Dashboard
        </a>
    </div>
    <div class="order-title mb-1">Order Details</div>
    <div class="order-number mb-1">Order #: OD<?= str_pad($order['order_id'], 6, "0", STR_PAD_LEFT) ?></div>
    <div class="mb-2">
        <?= statusBadge($order['status']) ?>
        <span class="ms-3 info-label">Placed on:</span>
        <span class="info-value"><?= htmlspecialchars($order['order_date']) ?></span>
    </div>
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="section-title">Customer Info</div>
            <div class="mb-2"><span class="info-label">Name:</span> <span class="info-value"><?= htmlspecialchars($order['username']) ?></span></div>
            <div class="mb-2"><span class="info-label">Phone:</span> <span class="info-value"><?= htmlspecialchars($order['phone']) ?></span></div>
            <div class="mb-2"><span class="info-label">Email:</span> <span class="info-value"><?= htmlspecialchars($order['email']) ?></span></div>
        </div>
        <div class="col-md-6">
            <div class="section-title">Order Info</div>
            <div class="mb-2"><span class="info-label">Delivery Time:</span> <span class="info-value"><?= htmlspecialchars($order['delivery_time']) ?></span></div>
            <div class="mb-2"><span class="info-label">Payment Type:</span> <span class="info-value"><?= htmlspecialchars($order['payment_type'] ?? 'N/A') ?></span></div>
            <div class="mb-2"><span class="info-label">Status:</span> <span class="info-value"><?= statusBadge($order['status']) ?></span></div>
        </div>
    </div>
    <div class="section-title">Order Items</div>
    <div class="table-responsive">
        <table class="table order-table align-middle table-bordered mb-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Food Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $i = 1; 
            $grand_total = 0;
            foreach ($items as $item): 
                // Tumia price_each, si price
                $price_each = isset($item['price_each']) ? $item['price_each'] : 0;
                $subtotal = $price_each * $item['quantity'];
                $grand_total += $subtotal;
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($item['food_name']) ?></td>
                    <td><?= intval($item['quantity']) ?></td>
                    <td><?= 'TSh ' . number_format($price_each, 0) ?></td>
                    <td><?= 'TSh ' . number_format($subtotal, 0) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="row mb-1">
        <div class="col-7 text-end"><b>Subtotal:</b></div>
        <div class="col-5"><?= 'TSh ' . number_format($grand_total, 0) ?></div>
    </div>
    <div class="row mb-1">
        <div class="col-7 text-end"><b>Delivery Fee:</b></div>
        <div class="col-5"><?= 'TSh ' . number_format($order['delivery_fee'] ?? 0, 0) ?></div>
    </div>
    <?php if(floatval($order['discount'] ?? 0) > 0): ?>
    <div class="row mb-1">
        <div class="col-7 text-end"><b>Discount:</b></div>
        <div class="col-5">-<?= 'TSh ' . number_format($order['discount'], 0) ?></div>
    </div>
    <?php endif; ?>
    <div class="row mb-2">
        <div class="col-7 text-end"><b>Total:</b></div>
        <div class="col-5 text-success"><b><?= 'TSh ' . number_format($order['total_amount'] ?? 0, 0) ?></b></div>
    </div>
</div>
</body>
</html>