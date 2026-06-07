<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) { die("Invalid order id"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'pending';
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->execute([$status, $order_id]);
    header("Location: orders.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id=?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$order) die("Order not found.");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #e6f7ee 0%, #f9fafb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .status-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 32px rgba(25,164,99,0.13), 0 1.5px 6px rgba(0,0,0,0.05);
            padding: 2.5rem;
            max-width: 450px;
            width: 100%;
        }
        .status-card h4 {
            color: #19a463;
            font-weight: 700;
            margin-bottom: 1.8rem;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-card h4 i {
            font-size: 1.5rem;
        }
        .form-select {
            border-radius: 10px;
            border: 2px solid #e0e5e2;
            padding: 12px 15px;
            font-size: 1rem;
            font-weight: 500;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-select:focus {
            border-color: #19a463;
            box-shadow: 0 0 0 3px rgba(25,164,99,0.1);
        }
        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 2rem;
        }
        .btn-update {
            flex: 1;
            background: linear-gradient(90deg, #19a463 60%, #14834e 100%);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 12px rgba(25,164,99,0.3);
        }
        .btn-update:hover {
            background: linear-gradient(90deg, #14834e 50%, #0a2a34 100%);
            box-shadow: 0 5px 18px rgba(25,164,99,0.4);
            transform: translateY(-2px);
        }
        .btn-update:active {
            transform: translateY(0);
        }
        .btn-back {
            flex: 1;
            background: #f3f8f6;
            color: #19a463;
            border: 2px solid #19a463;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-back:hover {
            background: #19a463;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 2px 12px rgba(25,164,99,0.3);
        }
        .btn-back:active {
            transform: scale(0.98);
        }
        .status-info {
            background: #e8f5e9;
            border-left: 4px solid #19a463;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="status-card">
    <h4>
        <i class="bi bi-pencil-square"></i>
        Change Order Status
    </h4>
    
    <div class="status-info">
        <strong>Order #OD<?=str_pad($order['order_id'],6,"0",STR_PAD_LEFT)?></strong>
        <br>
        Current Status: <strong><?=ucfirst($order['status'])?></strong>
    </div>

    <form method="post">
        <div class="mb-2">
            <label class="form-label" style="font-weight: 600; color: #19a463;">New Status</label>
            <select name="status" class="form-select" required>
                <option value="pending" <?=($order['status']=="pending"?"selected":"")?>>
                    <i class="bi bi-clock"></i> Pending
                </option>
                <option value="inprogress" <?=($order['status']=="inprogress"?"selected":"")?>>
                    <i class="bi bi-hourglass-split"></i> In Progress
                </option>
                <option value="delivered" <?=($order['status']=="delivered"?"selected":"")?>>
                    <i class="bi bi-check-circle"></i> Delivered
                </option>
                <option value="cancelled" <?=($order['status']=="cancelled"?"selected":"")?>>
                    <i class="bi bi-x-circle"></i> Cancelled
                </option>
            </select>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-update">
                <i class="bi bi-check-lg"></i> Update Status
            </button>
            <a href="orders.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>
</body>
</html>
