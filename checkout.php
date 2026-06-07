<?php
session_start();
require_once 'includes/db.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Blocked user check
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT status FROM users WHERE user_id=? LIMIT 1");
$stmt->execute([$user_id]);
$status = $stmt->fetchColumn();
if ($status === 'blocked') {
    echo '<div style="color: #d05943; font-weight: bold; margin:40px auto;max-width:400px;text-align:center;">Your account is blocked. You cannot place orders. <br>Contact support for help.</div>';
    exit();
}

// 3. Cart clean-up
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if (!isset($item['food_id']) || empty($item['food_id'])) {
            unset($_SESSION['cart'][$key]);
        }
    }
}
if (empty($_SESSION['cart'])) {
    $error_message = "Your cart is empty. Please add items again.";
}

// 4. Helper functions
function calculateCartTotal($cart) {
    $sum = 0;
    foreach ($cart as $item) {
        $sum += $item['price'] * $item['quantity'];
    }
    return $sum;
}

function applyPromoCode($code, $subtotal, $pdo) {
    $code = strtoupper(trim($code));
    if (!$code) return ['amount' => 0, 'desc' => ''];
    $stmt = $pdo->prepare("SELECT * FROM promo_codes WHERE UPPER(code)=? AND is_active=1 AND (valid_from IS NULL OR valid_from<=CURDATE()) AND (valid_to IS NULL OR valid_to>=CURDATE()) LIMIT 1");
    $stmt->execute([$code]);
    $promo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($promo) {
        if ($promo['discount_type'] == 'percent') {
            $amount = round($subtotal * ($promo['discount_value'] / 100));
            $desc = $promo['discount_value'] . '% Discount';
        } else {
            $amount = floatval($promo['discount_value']);
            $desc = 'TSh ' . number_format($amount, 0) . ' Off';
        }
        return ['amount' => min($amount, $subtotal), 'desc' => $desc];
    }
    return ['amount' => 0, 'desc' => ''];
}

define('CURRENCY_SYMBOL', 'TSh ');

// 5. User info
$username  = $_SESSION['username'] ?? '';
$email     = $_SESSION['email'] ?? '';
$phone     = $_SESSION['phone'] ?? '';
$delivery_fee = 2500;
$error_message = $error_message ?? '';
$order_number = '';
$expected_time = '';
$promo_code_applied = false;
$discount_amount = 0;
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart_total = calculateCartTotal($_SESSION['cart']);

// 6. Payment types
$tz_payments = [
    ['value'=>'cash','label'=>'Cash'],
    ['value'=>'mpesa','label'=>'M-Pesa'],
    ['value'=>'tigopesa','label'=>'Tigo Pesa'],
    ['value'=>'airtelmoney','label'=>'Airtel Money'],
    ['value'=>'halopesa','label'=>'HaloPesa'],
    ['value'=>'crdb','label'=>'CRDB Bank Card'],
    ['value'=>'nmb','label'=>'NMB Bank Card'],
    ['value'=>'card','label'=>'Credit/Debit Card'],
];

// 7. Promo code
$discount_info = applyPromoCode($_POST['promo_code'] ?? '', $cart_total, $pdo);
if ($discount_info['amount'] > 0) {
    $discount_amount = min($discount_info['amount'], $cart_total + $delivery_fee);
    $promo_code_applied = true;
} else {
    $promo_code_applied = false;
}
$subtotal   = $cart_total + $delivery_fee;
$total_amount = $subtotal - $discount_amount;

// 8. Delivery time calculation
$now = new DateTime();
$eta_minutes = ($_POST['delivery_time'] ?? '') === 'asap' ? 55 : 0;
if (isset($_POST['delivery_time']) && $_POST['delivery_time'] && $_POST['delivery_time'] != 'asap') {
    $delivery_at = DateTime::createFromFormat('H:i', $_POST['delivery_time']);
    if ($delivery_at && $delivery_at > $now) {
        $interval = $now->diff($delivery_at);
        $eta_minutes = ($interval->h * 60) + $interval->i;
    }
}
$expected_time = $now->modify("+$eta_minutes minutes")->format('H:i');

// 9. Order processing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error_message)) {
    $customer_name    = $_POST['customer_name']    ?? '';
    $user_phone       = $_POST['user_phone']       ?? '';
    $user_email       = $_POST['user_email']       ?? '';
    $delivery_address = $_POST['delivery_address'] ?? '';
    $delivery_time    = $_POST['delivery_time']    ?? '';
    $payment_method   = $_POST['payment_method']   ?? '';
    $order_notes      = $_POST['order_notes']      ?? '';
    $promo_code       = $_POST['promo_code']       ?? '';
    $terms_checked    = isset($_POST['terms']);

    $cart_total = calculateCartTotal($_SESSION['cart']);
    $subtotal   = $cart_total + $delivery_fee;
    $discount_info = applyPromoCode($promo_code, $cart_total, $pdo);
    if ($discount_info['amount'] > 0) {
        $discount_amount = min($discount_info['amount'], $subtotal);
        $promo_code_applied = true;
    } else {
        $promo_code_applied = false;
    }
    $total_amount = $subtotal - $discount_amount;

    $now = new DateTime();
    $eta_minutes = ($delivery_time == 'asap' || $delivery_time == '') ? 55 : 0;
    if ($delivery_time && $delivery_time != 'asap') {
        $delivery_at = DateTime::createFromFormat('H:i', $delivery_time);
        if ($delivery_at && $delivery_at > $now) {
            $interval = $now->diff($delivery_at);
            $eta_minutes = ($interval->h * 60) + $interval->i;
        }
    }
    $expected_time = $now->modify("+$eta_minutes minutes")->format('H:i');

    if (
        empty($customer_name) || empty($user_phone) || empty($user_email) || empty($delivery_address) ||
        empty($delivery_time) || empty($payment_method) || !$terms_checked
    ) {
        $error_message = "Please fill in all required fields and accept the Terms & Conditions.";
    } elseif (empty($_SESSION['cart'])) {
        $error_message = "Your cart is empty. Please add items again.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO orders
                (user_id, order_date, status, total_amount, customer_name, delivery_address, delivery_time,
                 payment_method, notes, email, phone, delivery_fee, discount, promo_code)
                VALUES (?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id, $total_amount, $customer_name, $delivery_address, $delivery_time,
                $payment_method, $order_notes, $user_email, $user_phone,
                $delivery_fee, $discount_amount, $promo_code
            ]);
            $order_id = $pdo->lastInsertId();

            foreach ($_SESSION['cart'] as $item) {
                $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt_item->execute([
                    $order_id,
                    $item['food_id'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            $_SESSION['cart'] = [];
            $order_number = "OD" . str_pad($order_id, 6, "0", STR_PAD_LEFT);

            // Email notification function (optional)
            // sendOrderMail($user_email, $order_number, $expected_time);

            header("Location: order_success.php?order=$order_number&eta=$expected_time");
            exit();

        } catch (PDOException $e) {
            $error_message = "There was a problem placing your order: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Food Delivery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #e6f7ee 0%, #f9fafb 100%);
            min-height: 100vh;
        }
        .checkout-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px 0 rgba(25,164,99,0.13), 0 1.5px 6px 0 rgba(0,0,0,0.05);
            padding: 2.5rem 2.2rem 2rem 2.2rem;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .main-green { color: #19a463 !important; }
        .section-title { color: #14834e; margin-top: 2.3rem; margin-bottom: .8rem; font-weight: 600; }
        .back-cart-link {
            display: inline-block; background: #f7faf7; border: 1.5px solid #19a463;
            color: #19a463; border-radius: 7px; padding: 8px 18px;
            font-weight: 500; text-decoration: none;
            margin-bottom: 2rem; transition: all 0.15s;
        }
        .back-cart-link:hover { background: #19a463; color: #fff; }
        .input-group-text { background: #f3f8f6; color: #14834e; border: none; }
        .form-control, .form-select { border-radius: 7px; box-shadow: none !important; border: 1.5px solid #e0e5e2; }
        .form-control:focus, .form-select:focus { border-color: #19a463; }
        .order-review-table th { background: #eafcf3; color: #19a463; }
        .order-review-table td, .order-review-table th { vertical-align: middle; }
        .order-review-table { font-size: 1rem; }
        .promo-success { color: #19a463; font-size: 0.97rem; }
        .promo-fail { color: #d05943; font-size: 0.97rem; }
        .btn-main {
            background: linear-gradient(90deg, #19a463, #14834e);
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 12px #19a46335;
            letter-spacing: .5px;
            font-size: 1.18rem;
            padding: 14px 0;
            transition: background .15s, box-shadow .15s;
        }
        .btn-main:hover { background: #14834e; box-shadow: 0 5px 18px #19a46334; }
        .eta { font-weight: 500; color: #14834e; }
        .form-label.required:after { content: " *"; color: #d05943; }
        @media (max-width: 800px) {
            .checkout-card { padding: 1.2rem .6rem 1.6rem .6rem; }
        }
        @media (max-width: 600px) {
            .checkout-card { margin-top:10px; margin-bottom:10px; }
        }
        .checkout-heading {
            text-align: center;
            margin-bottom: 2rem;
        }
        .checkout-icon-wrap {
            display: inline-block;
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: linear-gradient(135deg, #19a463 60%, #14834e 100%);
            box-shadow: 0 4px 16px #19a46322;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .checkout-icon-wrap i {
            font-size: 2rem;
            color: #fff;
        }
        .checkout-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #14834e;
            letter-spacing: .5px;
            margin-top: .1rem;
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        }
    </style>
</head>
<body>
<div class="container" style="max-width:760px;">
    <div class="checkout-card">
       <div class="checkout-heading mb-3">
            <div class="checkout-icon-wrap mb-2">
                <i class="bi bi-bag-check"></i>
            </div>
            <div class="checkout-title">Checkout</div>
        </div>
        <a href="cart.php" class="back-cart-link"><i class="bi bi-arrow-left"></i> Return to Cart</a>
        <?php if($error_message): ?>
            <div class="alert alert-danger mt-2"><?= $error_message ?></div>
        <?php endif; ?>
        <form method="POST" action="checkout.php" autocomplete="on">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="section-title">1. User Information</div>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label required">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                   value="<?= htmlspecialchars($_POST['customer_name'] ?? $username) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="user_phone" class="form-label required">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" class="form-control" id="user_phone" name="user_phone"
                                   value="<?= htmlspecialchars($_POST['user_phone'] ?? $phone) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label required">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="user_email" name="user_email"
                                   value="<?= htmlspecialchars($_POST['user_email'] ?? $email) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="delivery_address" class="form-label required">Delivery Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" id="delivery_address" name="delivery_address"
                                   value="<?= htmlspecialchars($_POST['delivery_address'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-title">2. Delivery Details</div>
                    <div class="mb-3">
                        <label for="delivery_time" class="form-label required">Delivery Time</label>
                        <select class="form-select" id="delivery_time" name="delivery_time" required>
                            <option value="">Select time</option>
                            <option value="asap" <?= (($_POST['delivery_time'] ?? '') === 'asap') ? 'selected' : '' ?>>ASAP (as soon as possible)</option>
                            <option value="10:00" <?= (($_POST['delivery_time'] ?? '') === '10:00') ? 'selected' : '' ?>>10:00 AM</option>
                            <option value="12:00" <?= (($_POST['delivery_time'] ?? '') === '12:00') ? 'selected' : '' ?>>12:00 PM</option>
                            <option value="14:00" <?= (($_POST['delivery_time'] ?? '') === '14:00') ? 'selected' : '' ?>>2:00 PM</option>
                            <option value="16:00" <?= (($_POST['delivery_time'] ?? '') === '16:00') ? 'selected' : '' ?>>4:00 PM</option>
                            <option value="18:00" <?= (($_POST['delivery_time'] ?? '') === '18:00') ? 'selected' : '' ?>>6:00 PM</option>
                        </select>
                        <div class="eta mt-1">Estimated delivery time: <b><?= $expected_time ?></b></div>
                    </div>
                    <div class="section-title">3. Payment Method</div>
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach($tz_payments as $pay): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" value="<?= $pay['value'] ?>" id="pay_<?= $pay['value'] ?>" <?= (($_POST['payment_method'] ?? '') === $pay['value']) ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="pay_<?= $pay['value'] ?>"><?= $pay['label'] ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div id="payment-instructions" class="mt-2" style="display:none;"></div>
                    </div>
                    <div class="section-title">4. Promo / Discount Code</div>
                    <div class="mb-3">
                        <label for="promo_code" class="form-label">Promo Code</label>
                        <input type="text" class="form-control" id="promo_code" name="promo_code"
                               value="<?= htmlspecialchars($_POST['promo_code'] ?? '') ?>" placeholder="Enter if you have one">
                        <?php if(isset($_POST['promo_code']) && $_POST['promo_code']): ?>
                            <?php if($promo_code_applied): ?>
                                <div class="promo-success">Code "<b><?= htmlspecialchars($_POST['promo_code']) ?></b>" applied (<?= $discount_info['desc'] ?>)</div>
                            <?php else: ?>
                                <div class="promo-fail">Code not recognized or not valid.</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="section-title">5. Order Notes (Optional)</div>
                    <div class="mb-3">
                        <label for="order_notes" class="form-label">Special Instructions</label>
                        <textarea class="form-control" id="order_notes" name="order_notes" placeholder="Eg: Call me before delivery or Leave at reception"><?= htmlspecialchars($_POST['order_notes'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            <div class="section-title">6. Order Review</div>
            <div class="mb-2">
                <table class="table order-review-table table-bordered align-middle mb-2">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= intval($item['quantity']) ?></td>
                            <td><?= CURRENCY_SYMBOL . number_format($item['price'], 0) ?></td>
                            <td><?= CURRENCY_SYMBOL . number_format($item['price'] * $item['quantity'], 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row mb-1">
                    <div class="col-7 text-end"><b>Subtotal:</b></div>
                    <div class="col-5"><?= CURRENCY_SYMBOL . number_format($cart_total, 0) ?></div>
                </div>
                <div class="row mb-1">
                    <div class="col-7 text-end"><b>Delivery fee:</b></div>
                    <div class="col-5"><?= CURRENCY_SYMBOL . number_format($delivery_fee, 0) ?></div>
                </div>
                <?php if($discount_amount > 0): ?>
                <div class="row mb-1">
                    <div class="col-7 text-end"><b>Discount:</b></div>
                    <div class="col-5">-<?= CURRENCY_SYMBOL . number_format($discount_amount, 0) ?></div>
                </div>
                <?php endif; ?>
                <div class="row mb-2">
                    <div class="col-7 text-end"><b>Total:</b></div>
                    <div class="col-5 main-green"><b><?= CURRENCY_SYMBOL . number_format($total_amount, 0) ?></b></div>
                </div>
            </div>
            <div class="form-check terms mt-3 mb-3">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" <?= isset($_POST['terms']) ? 'checked' : '' ?> required>
                <label class="form-check-label" for="terms">
                    I accept the <a href="terms.php" target="_blank" style="color:#19a463;text-decoration:underline;">Terms & Conditions</a>
                </label>
            </div>
            <button type="submit" class="btn btn-main w-100 mt-3"><i class="bi bi-check-circle"></i> Confirm Order</button>
        </form>
    </div>
</div>
<script>
document.querySelectorAll('input[name="payment_method"]').forEach(function(elem){
    elem.addEventListener('change', function(){
        let val = this.value;
        let container = document.getElementById('payment-instructions');
        let html = "";
        if(val === "cash") {
            html = "<div class='alert alert-info mb-2'>You have chosen <b>Cash</b>. Please prepare cash to pay the delivery agent upon receiving your order.</div>";
        }
        else if(val === "mpesa") {
            html = "<div class='alert alert-success mb-2'>You have chosen <b>M-Pesa</b>.<br>Send payment to:<br><b>0767 999 999</b> (Jina: FOOD DELIVERY)<br>Utapokea maelekezo zaidi kupitia SMS baada ya kuthibitisha order.</div>";
        }
        else if(val === "tigopesa") {
            html = "<div class='alert alert-success mb-2'>You have chosen <b>Tigo Pesa</b>.<br>Send payment to:<br><b>0655 888 888</b> (Jina: FOOD DELIVERY)</div>";
        }
        else if(val === "airtelmoney") {
            html = "<div class='alert alert-success mb-2'>You have chosen <b>Airtel Money</b>.<br>Send payment to:<br><b>0788 777 777</b> (Jina: FOOD DELIVERY)</div>";
        }
        else if(val === "halopesa") {
            html = "<div class='alert alert-success mb-2'>You have chosen <b>HaloPesa</b>.<br>Send payment to:<br><b>0679 666 666</b> (Jina: FOOD DELIVERY)</div>";
        }
        else if(val === "crdb" || val === "nmb" || val === "card") {
            html = "<div class='alert alert-primary mb-2'>You have chosen <b>Bank Card</b>.<br>Card payment will be processed upon delivery or you will be contacted for payment instructions.</div>";
        }
        container.innerHTML = html;
        container.style.display = html ? 'block' : 'none';
    });
});
let checked = document.querySelector('input[name="payment_method"]:checked');
if(checked) checked.dispatchEvent(new Event('change'));
</script>
</body>
</html>