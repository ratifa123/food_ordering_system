<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!$username || !$email || !$password) {
        $error = "Fill all required fields.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $phone, $hash, $role]);
        header("Location: users.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width:500px;">
    <h2>Add User</h2>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username *</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="user" <?= (($_POST['role'] ?? '')=='user') ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= (($_POST['role'] ?? '')=='admin') ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add User</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>