<?php
session_start();
require_once 'includes/db.php';

$errors = [];
$redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? 'checkout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $errors[] = "Please enter email/username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT user_id, username, email, phone, password, status FROM users WHERE email = ? OR username = ? LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Invalid credentials.";
        } elseif ($user['status'] === 'blocked') {
            $errors[] = "Account blocked. Contact support.";
        } else {
            // Successful login
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone'] = $user['phone'];

            // Basic safety: only allow internal redirects
            if (strpos($redirect, '/') === 0 || preg_match('#^[a-z0-9_\-\.]+\.php$#i', $redirect)) {
                header("Location: " . $redirect);
            } else {
                header("Location: checkout.php");
            }
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Morogoro Taste Food</title>
    <style>
        :root { --main-green: #19a463; --main-bg: #f5f6fa; --card:#fff; }
        body{font-family:Poppins, sans-serif;background:var(--main-bg);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
        form{background:var(--card);padding:32px;border-radius:14px;width:360px;box-shadow:0 8px 30px #00000010}
        h2{text-align:center;color:#14834e;margin-bottom:18px}
        input{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;margin-bottom:12px}
        button{width:100%;padding:12px;background:var(--main-green);color:#fff;border:none;border-radius:30px;font-weight:700;cursor:pointer}
        .errors{background:#f87171;color:#fff;padding:10px;border-radius:8px;margin-bottom:12px}
        .link{text-align:center;margin-top:10px}
        .link a{color:var(--main-green);text-decoration:none;font-weight:600}
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <input type="text" name="identifier" placeholder="Email or Username" required value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>">
        <input type="password" name="password" placeholder="Password" required>
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
        <button type="submit">Login</button>

        <div class="link">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </form>
</body>
</html>
