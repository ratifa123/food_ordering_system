<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$msg = "";
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM promo_codes WHERE promo_id=?");
$stmt->execute([$id]);
$promo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$promo) {
    die("Promo code not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $description = trim($_POST['description']);
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $valid_from = $_POST['valid_from'];
    $valid_to = $_POST['valid_to'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Check unique code except current
    $exists = $pdo->prepare("SELECT promo_id FROM promo_codes WHERE code=? AND promo_id!=?");
    $exists->execute([$code, $id]);
    if ($exists->fetchColumn()) {
        $msg = "Promo code already exists!";
    } else {
        $stmt = $pdo->prepare("UPDATE promo_codes SET code=?,description=?,discount_type=?,discount_value=?,valid_from=?,valid_to=?,is_active=? WHERE promo_id=?");
        $stmt->execute([$code, $description, $discount_type, $discount_value, $valid_from, $valid_to, $is_active, $id]);
        header("Location: promos.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Promo Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Promo Code</h2>
    <?php if($msg): ?>
        <div class="alert alert-danger"><?= $msg ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($promo['code']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($promo['description']) ?>">
        </div>
        <div class="mb-3">
            <label>Discount Type</label>
            <select name="discount_type" class="form-control">
                <option value="amount" <?= $promo['discount_type']=='amount'?'selected':'' ?>>Amount</option>
                <option value="percent" <?= $promo['discount_type']=='percent'?'selected':'' ?>>Percent</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Discount Value</label>
            <input type="number" step="0.01" name="discount_value" class="form-control" value="<?= $promo['discount_value'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Valid From</label>
            <input type="date" name="valid_from" class="form-control" value="<?= $promo['valid_from'] ?>">
        </div>
        <div class="mb-3">
            <label>Valid To</label>
            <input type="date" name="valid_to" class="form-control" value="<?= $promo['valid_to'] ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" <?= $promo['is_active']?'checked':'' ?>>
            <label class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="promos.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>