<?php
session_start();
require_once 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username or email already taken.";
        } else {
            // Insert new user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $phone, $password_hash]);

            // Redirect to login page
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Register - Morogoro Taste Food</title>
    <style>
       :root {
            --main-green: #19a463;
            --main-green-dark: #14834e;
            --main-bg: #f5f6fa;
            --main-card: #fff;
            --main-shadow: 0 6px 24px #19a46313;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--main-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        form {
            background: var(--main-card);
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: var(--main-shadow);
            width: 380px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--main-green-dark);
            font-size: 1.8rem;
        }

        input[type=text], input[type=email], input[type=tel], input[type=password] {
            width: 100%;
            padding: 10px 12px;
            margin: 10px 0 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: var(--main-green);
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
        }

        .errors {
            background: #f87171;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 10px;
            font-weight: 600;
            color: #fff;
        }

        .success {
            background: #34d399;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 10px;
            font-weight: 600;
            color: #fff;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: var(--main-green);
            text-decoration: none;
            font-weight: 600;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Register</h2>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <input type="tel" name="phone" placeholder="Phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <button type="submit">Register</button>
        <div class="link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </form>
</body>
</html>