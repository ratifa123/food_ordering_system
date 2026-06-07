<?php
require_once '../includes/db.php';

$username = 'admin';
$password = 'adminpass123'; // password yako mpya
$fullname = 'Main Admin';
$email = 'admin@email.com';

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO admins (username, password, fullname, email) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $hash, $fullname, $email]);

echo "Admin created! Username: $username";
?>