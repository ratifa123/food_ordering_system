<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);
$user = $pdo->prepare("SELECT * FROM users WHERE user_id=?");
$user->execute([$id]);
$data = $user->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("User not found.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? $data['role'];
    $password = $_POST['password'] ?? '';

    if (!$username || !$email) {
        $error = "Fill all required fields.";
    } else {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, phone=?, password=?, role=? WHERE user_id=?");
            $stmt->execute([$username, $email, $phone, $hash, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, phone=?, role=? WHERE user_id=?");
            $stmt->execute([$username, $email, $phone, $role, $id]);
        }
        header("Location: users.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width:500px;">
    <h2>Edit User</h2>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username *</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($data['username']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($data['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="user" <?= ($data['role']=='user') ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= ($data['role']=='admin') ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>