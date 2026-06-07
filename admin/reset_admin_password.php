<?php
/**
 * Admin Password Reset Script
 * Run this script once to reset admin password, then delete it
 * 
 * Usage: 
 * 1. Access this file in browser: http://yoursite.com/reset_admin_password.php
 * 2. Enter new password
 * 3. Password will be updated
 * 4. DELETE this file after use for security
 */

session_start();
require_once '../includes/db.php';

$message = '';
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $security_key = $_POST['security_key'] ?? '';
    
    // Simple security - replace 'your-secret-key' with something only you know
    $SECURITY_KEY = 'admin123morogoro';
    
    if ($security_key !== $SECURITY_KEY) {
        $error = '❌ Invalid security key. Access denied.';
    } elseif (empty($new_password) || empty($confirm_password)) {
        $error = '❌ Please fill in all fields.';
    } elseif (strlen($new_password) < 8) {
        $error = '❌ Password must be at least 8 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error = '❌ Passwords do not match.';
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        try {
            // Update admin password (admin_id = 4)
            $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE admin_id = 4");
            $stmt->execute([$hashed_password]);
            
            $message = '✅ Admin password updated successfully! Please delete this file now and login with your new password.';
        } catch (PDOException $e) {
            $error = '❌ Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Password Reset</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .container h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        .container p {
            color: #666;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        button:active {
            transform: translateY(0);
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #ff9800;
        }
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Password Reset</h1>
        <p>Reset your admin account password</p>

        <div class="warning">
            ⚠️ <strong>Security Warning:</strong> Please delete this file immediately after resetting your password!
        </div>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$message): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="security_key">Security Key</label>
                    <input 
                        type="password" 
                        id="security_key" 
                        name="security_key" 
                        placeholder="Enter security key" 
                        required
                    >
                    <small style="color: #999; display: block; margin-top: 5px;">
                        Ask your system administrator for the security key
                    </small>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        placeholder="Enter new password (min 8 characters)" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Confirm new password" 
                        required
                    >
                </div>

                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>

        <div class="footer">
            <p>Made with ❤️ by Morogoro Taste Food</p>
        </div>
    </div>
</body>
</html>
