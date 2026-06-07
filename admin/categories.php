<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY category_id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7fafb; }
        .card { background: #fff; box-shadow: 0 2px 14px #14834e18; border-radius: 12px; max-width: 650px; margin: 48px auto; padding:2rem;}
        .section-title { font-weight: 700; color: #19a463; margin-bottom: 1.2rem; font-size:1.22rem;}
        .add-link {
            display: inline-block;
            margin-bottom: 18px;
            font-weight: 500;
            color: #fff;
            background: #11b981;
            padding: .6em 1.3em;
            border-radius: 11px;
            text-decoration: none;
        }
        .add-link:hover {background:#14834e; color:#fff;}
        .dashboard-link {
            display: inline-block;
            margin-bottom: 18px;
            font-weight: 500;
            color: #11b981;
            background: #eafcf3;
            padding: .6em 1.3em;
            border-radius: 11px;
            text-decoration: none;
            transition: .17s;
        }
        .dashboard-link:hover {
            background: #11b981;
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <a href="dashboard.php" class="dashboard-link"><i class="bi bi-house"></i> Back to Dashboard</a>
    <div class="section-title">Manage Categories</div>
    <a href="category_add.php" class="add-link">+ Add Category</a>
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($categories as $cat): ?>
            <tr>
                <td><?= $cat['category_id'] ?></td>
                <td><?= htmlspecialchars($cat['category_name']) ?></td>
                <td><?= date('d M Y H:i', strtotime($cat['created_at'])) ?></td>
                <td>
                    
                    <a href="category_delete.php?id=<?= $cat['category_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete category?')">Delete</a>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>