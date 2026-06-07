<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

// Handle deletion
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM promo_codes WHERE promo_id=?")->execute([$id]);
    header("Location: promos.php");
    exit;
}

// Fetch all promo codes
$promos = $pdo->query("SELECT * FROM promo_codes ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Promo Codes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Promo Codes
        <a href="add_promo.php" class="btn btn-success btn-sm float-end">Add Promo Code</a>
    </h2>
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Description</th>
                <th>Type</th>
                <th>Value</th>
                <th>Validity</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($promos as $p): ?>
            <tr>
                <td><?= $p['promo_id'] ?></td>
                <td><?= htmlspecialchars($p['code']) ?></td>
                <td><?= htmlspecialchars($p['description']) ?></td>
                <td><?= ucfirst($p['discount_type']) ?></td>
                <td><?= $p['discount_value'] ?></td>
                <td>
                    <?= $p['valid_from'] ?> to <?= $p['valid_to'] ?>
                </td>
                <td>
                    <?php if($p['is_active']): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_promo.php?id=<?= $p['promo_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_promo.php?id=<?= $p['promo_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this promo code?')">Delete</a>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<!-- Add Bootstrap Icons CDN for the arrow icon if needed -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>