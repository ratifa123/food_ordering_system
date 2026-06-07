<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $description = trim($_POST['description']);
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $valid_from = $_POST['valid_from'];
    $valid_to = $_POST['valid_to'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Check unique code
    $exists = $pdo->prepare("SELECT promo_id FROM promo_codes WHERE code=?");
    $exists->execute([$code]);
    if ($exists->fetchColumn()) {
        $msg = "Promo code already exists!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO promo_codes (code,description,discount_type,discount_value,valid_from,valid_to,is_active) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$code, $description, $discount_type, $discount_value, $valid_from, $valid_to, $is_active]);
        header("Location: promos.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Promo Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add Promo Code</h2>
    <?php if($msg): ?>
        <div class="alert alert-danger"><?= $msg ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <input type="text" name="description" class="form-control">
        </div>
        <div class="mb-3">
            <label>Discount Type</label>
            <select name="discount_type" class="form-control">
                <option value="amount">Amount</option>
                <option value="percent">Percent</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Discount Value</label>
            <input type="number" step="0.01" name="discount_value" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Valid From</label>
            <input type="date" name="valid_from" class="form-control">
        </div>
        <div class="mb-3">
            <label>Valid To</label>
            <input type="date" name="valid_to" class="form-control">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" checked>
            <label class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-success">Add Promo Code</button>
        <a href="promos.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>