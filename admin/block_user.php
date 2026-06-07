<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Invalid user ID.");
}

// Block user (set status to 'blocked')
$stmt = $pdo->prepare("UPDATE users SET status='blocked' WHERE user_id=?");
$stmt->execute([$id]);

header("Location: users.php?blocked=$id");
exit;
?>