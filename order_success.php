<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_number = $_GET['order'] ?? '';
$expected_time = $_GET['eta'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success | Food Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: #f4f6f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px 0 rgba(25,164,99,0.13), 0 2px 10px 0 rgba(0,0,0,0.04);
            padding: 2.5rem 2.2rem 2.2rem 2.2rem;
            max-width: 420px;
            width: 100%;
            margin: 3rem auto;
        }
        .success-icon {
            font-size: 3.5rem;
            color: #fff;
            background: linear-gradient(135deg, #19a463 70%, #14834e 100%);
            border-radius: 50%;
            width: 78px;
            height: 78px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem auto;
            box-shadow: 0 4px 16px #19a46322;
        }
        .order-number {
            font-size: 1.13rem;
            font-weight: 600;
            color: #19a463;
            margin-bottom: .7rem;
            word-break: break-all;
            letter-spacing: 1px;
        }
        .eta {
            font-size: 1.09rem;
            color: #14834e;
            font-weight: 500;
            margin-bottom: 1.2rem;
        }
        h2 {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            color: #14834e;
            font-weight: 700;
            margin-bottom: 1.1rem;
            letter-spacing: .2px;
            font-size: 2rem;
        }
        .thankyou-msg {
            color:#14834e;
            font-size:1.05rem;
            margin-bottom:2rem;
            font-weight: 500;
        }
        .btn-main, .btn-outline-success {
            width: 100%;
            margin: 0.3rem 0;
        }
        .btn-main {
            background: linear-gradient(90deg, #19a463, #14834e);
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 1.06rem;
            padding: 12px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px #19a46318;
            letter-spacing: .2px;
        }
        .btn-main:hover {
            background: #14834e;
            box-shadow: 0 7px 18px #19a46314;
            color: #fff;
        }
        .btn-outline-success {
            border-radius: 8px;
        }
        .alert-info {
            font-size: 1.02rem;
            margin-top: 1.3rem;
        }
        @media (max-width: 600px) {
            .success-card {
                padding: 1.1rem .7rem 1.2rem .7rem;
                max-width: 99vw;
                margin: 1rem auto;
            }
            .success-icon {
                font-size: 2.1rem;
                width: 48px;
                height: 48px;
                margin-bottom: .7rem;
            }
            h2 {
                font-size: 1.18rem;
            }
        }
    </style>
</head>
<body>
<div class="success-card text-center">
    <div class="success-icon mb-2">
        <i class="bi bi-bag-check-fill"></i>
    </div>
    <h2>Your Order is Confirmed!</h2>
    <div class="order-number">
        Order Number: <span><?= htmlspecialchars($order_number) ?></span>
    </div>
    <div class="eta">
        Estimated delivery: <b><?= htmlspecialchars($expected_time) ?></b>
    </div>
    <div class="thankyou-msg">
        Thank you for your order.<br>
        Please keep your Order Number for reference.<br>
        If you need help, call/WhatsApp <b>+255 7XX XXX XXX</b>.
    </div>
    <div class="alert alert-info">
        <b>NOTE:</b> Please save your order number. Our team may contact you via SMS or call to confirm your order.<br>
        If you do not receive a call within 10 minutes, feel free to contact us directly.
    </div>
    <a href="index.php" class="btn btn-main mt-2"><i class="bi bi-arrow-left"></i> Back to Menu</a>
    <a href="my_orders.php" class="btn btn-outline-success mt-2">View My Orders</a>
</div>
</body>
</html>