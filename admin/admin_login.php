<?php
session_start();
require_once '../includes/db.php';

$error = '';
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_fullname'] = $admin['fullname'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Food Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(120deg, #11b981 0%, #63d7b1 100%); height: 100vh;}
        .login-box {
            background: #fff;
            max-width: 400px;
            margin: 90px auto 0;
            border-radius: 18px;
            padding: 2.5rem 2.2rem 2rem;
            box-shadow: 0 8px 30px rgba(17,185,129,0.12), 0 1.5px 6px 0 rgba(0,0,0,0.07);
        }
        .login-title { font-size: 2rem; color: #11b981; font-weight: 700; margin-bottom: 22px; text-align: center;}
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-title">Admin Login</div>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required autocomplete="off">
            </div>
            <button type="submit" name="login" class="btn btn-success w-100">Login</button>
        </form>
    </div>
</body>
</html>