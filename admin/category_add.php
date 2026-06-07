<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    if ($category_name) {
        // Check if exists
        $chk = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE category_name=?");
        $chk->execute([$category_name]);
        if ($chk->fetchColumn() > 0) {
            $msg = '<div class="alert alert-danger">Category already exists!</div>';
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->execute([$category_name]);
            $msg = '<div class="alert alert-success">Category added successfully.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Category name required.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Category | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {background:#f7fafb;}
        .card {background:#fff;box-shadow:0 2px 14px #14834e18;border-radius:12px;max-width:410px;margin:50px auto;padding:2rem;}
        .form-label {font-weight:500;color:#14834e;}
        .back-link {display:inline-block;margin-bottom:18px;font-weight:500;color:#11b981;background:#eafcf3;padding:.6em 1.3em;border-radius:11px;text-decoration:none;}
        .back-link:hover {background:#11b981;color:#fff;}
    </style>
</head>
<body>
<div class="card">
    <a href="categories.php" class="back-link">&larr; Back to Categories</a>
    <h4 style="color:#19a463;font-weight:700;">Add Category</h4>
    <?= $msg ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Category Name *</label>
            <input type="text" name="category_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Add Category</button>
    </form>
</div>
</body>
</html>