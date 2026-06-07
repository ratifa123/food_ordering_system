<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($food_id <= 0) { die("Invalid food id"); }

// Pata details za chakula
$stmt = $pdo->prepare("SELECT * FROM food_items WHERE food_id=?");
$stmt->execute([$food_id]);
$food = $stmt->fetch();
if (!$food) { die("Food item not found."); }

// Pata categories zote
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $price       = floatval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $image_url   = trim($_POST['image_url'] ?? '');

    if ($name && $price > 0 && $category_id > 0) {
        $stmt = $pdo->prepare("UPDATE food_items SET name=?, price=?, category_id=?, image_url=?, is_active=? WHERE food_id=?");
        $stmt->execute([$name, $price, $category_id, $image_url, $is_active, $food_id]);
        header("Location: foods.php");
        exit();
    } else {
        $error = "Fill all fields correctly!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Food | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7fafb; }
        .card { background: #fff; box-shadow: 0 2px 14px #14834e18; border-radius: 12px; max-width: 500px; margin: 38px auto; padding:2rem;}
        .section-title { font-weight: 700; color: #19a463; margin-bottom: 1.2rem; font-size:1.18rem;}
    </style>
</head>
<body>
<div class="card">
    <div class="section-title">Edit Food</div>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Food Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? $food['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (TSh)</label>
            <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($_POST['price'] ?? $food['price']) ?>" required min="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select category</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"
                        <?= (($cat['category_id'] == ($food['category_id'])) || ($cat['category_id'] == ($_POST['category_id'] ?? 0))) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL</label>
            <input type="text" name="image_url" class="form-control" value="<?= htmlspecialchars($_POST['image_url'] ?? $food['image_url']) ?>">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" id="activeCheck"
                <?= (($food['is_active'] ?? 1) || isset($_POST['is_active'])) ? 'checked' : '' ?>>
            <label class="form-check-label" for="activeCheck">Active</label>
        </div>
        <button type="submit" class="btn btn-success">Save Changes</button>
        <a href="foods.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
</body>
</html>