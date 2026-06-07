<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

// Fetch categories for dropdown
$cat_stmt = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $image_url = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $fname = 'images/' . uniqid('food_',true) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../' . $fname)) {
                $image_url = $fname;
            }
        }
    }

    if ($name && $category_id && $price >= 0) {
        $stmt = $pdo->prepare("INSERT INTO food_items (name, price, image_url, category_id, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $image_url, $category_id, $is_active]);
        $msg = '<div class="alert alert-success">Food item added successfully.</div>';
    } else {
        $msg = '<div class="alert alert-danger">Please fill all required fields and enter a valid price.</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Food | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7fafb; }
        .add-card { background: #fff; border-radius: 13px; box-shadow:0 2px 18px #11b98118; max-width:410px; margin: 48px auto; padding:2.1rem 2rem 1.5rem 2rem;}
        .form-label { font-weight: 500; color: #14834e;}
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
<div class="add-card">
    <a href="foods.php" class="dashboard-link"><i class="bi bi-arrow-left"></i> Back to Foods</a>
    <h4 class="mb-3" style="color:#11b981;font-weight:700;">Add New Food Item</h4>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-2">
            <label class="form-label">Food Image (optional)</label>
            <div>
                <img id="previewImg" src="assets/no-image.png" style="width:120px;height:120px;object-fit:cover;border-radius:13px;margin-bottom:8px;" alt="Preview">
            </div>
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewFile(this)">
        </div>
        <div class="mb-2">
            <label class="form-label">Food Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Price (TSh) *</label>
            <input type="number" name="price" class="form-control" min="0" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- select --</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="mb-2 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" checked>
            <label class="form-check-label">Active</label>
        </div>
        <button type="submit" class="btn btn-success w-100">Add Food</button>
    </form>
</div>
<script>
function previewFile(input){
    var file = input.files[0];
    var preview = document.getElementById('previewImg');
    if(file){
        var reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = "assets/no-image.png";
    }
}
</script>
</body>
</html>