<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];

// SELECT lazima ichukue order_id, sio id
$stmt = $pdo->prepare("SELECT order_id, order_date, status, total_amount, delivery_time FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Food Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f7fafb; }
        .orders-card {
            background: #fff;
            box-shadow: 0 3px 18px #19a46318;
            border-radius: 16px;
            padding: 2.3rem 1.2rem;
            margin: 40px auto;
            max-width: 850px;
        }
        .order-row { border-bottom: 1.5px solid #e9ecef; }
        .order-status { font-weight: 600; }
        .status-pending { color: #d59c18; }
        .status-inprogress { color: #2c91e2; }
        .status-delivered { color: #19a463; }
        .status-cancelled { color: #d05943; }
        .order-number { font-weight: 600; color: #14834e; font-size: 1.09rem;}
        .table th, .table td { vertical-align: middle; }
        .orders-title {
            color: #14834e;
            font-size: 2.2rem;
            margin-bottom: 1.3rem;
            font-weight: 700;
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        }
        .badge-status {
            font-size: .98rem;
            padding: .49em 1.15em;
            border-radius: 14px;
            font-weight: 600;
        }
        .badge-pending { background: #fff8e2; color: #d59c18; border: 1px solid #ffe49e;}
        .badge-inprogress { background: #e7f1fa; color: #2c91e2; border: 1px solid #b0d4f7;}
        .badge-delivered { background: #eafcf3; color: #19a463; border: 1px solid #b8f0d1;}
        .badge-cancelled { background: #fdecea; color: #d05943; border: 1px solid #fbbcb1;}
        .table-responsive { border-radius: 12px; overflow: hidden;}
        @media (max-width: 650px) {
            .orders-title { font-size: 1.27rem; }
            .orders-card { padding: 1.2rem .3rem; }
            .table th, .table td { font-size: .92rem; }
        }
    </style>
</head>
<body>
<div class="orders-card">
    <div class="orders-title">My Orders</div>
    <div class="mb-3">
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Menu
        </a>
    </div>
    <?php if(empty($orders)): ?>
        <div class="alert alert-info">You have not placed any orders yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Delivery</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($orders as $order): ?>
                    <tr class="order-row">
                        <td class="order-number">
                            <?php
                            // Badilisha hapa: tumia order_id badala ya id
                            $num = isset($order['order_id']) ? str_pad($order['order_id'], 6, "0", STR_PAD_LEFT) : "------";
                            echo "OD".$num;
                            ?>
                        </td>
                        <td><?= date('d M Y H:i', strtotime($order['order_date'])) ?></td>
                        <td>
                            <?php
                            $status = strtolower($order['status']);
                            $badgeClass = 'badge-status badge-' . $status;
                            echo '<span class="'.$badgeClass.'">'.ucfirst($order['status']).'</span>';
                            ?>
                        </td>
                        <td>
                            <?= 'TSh ' . number_format($order['total_amount'], 0) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($order['delivery_time']) ?>
                        </td>
                        <td>
                            <?php if(isset($order['order_id'])): ?>
                                <a href="order_view.php?id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-success">View</a>
                            <?php else: ?>
                                <span class="text-danger">No Details</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>