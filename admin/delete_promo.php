<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("DELETE FROM promo_codes WHERE promo_id=?")->execute([$id]);
    header("Location: promos.php");
    exit;
}

// Get promo code info for confirmation
$stmt = $pdo->prepare("SELECT * FROM promo_codes WHERE promo_id=?");
$stmt->execute([$id]);
$promo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$promo) {
    die("Promo code not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Promo Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Delete Promo Code</h2>
    <div class="alert alert-danger">
        Are you sure you want to delete promo code <strong><?= htmlspecialchars($promo['code']) ?></strong>?
    </div>
    <form method="POST">
        <button type="submit" class="btn btn-danger">Yes, Delete</button>
        <a href="promos.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>