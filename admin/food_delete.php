<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($food_id <= 0) { die("Invalid food id"); }

// Optional: Check if food exists
$stmt = $pdo->prepare("SELECT * FROM food_items WHERE food_id=?");
$stmt->execute([$food_id]);
$food = $stmt->fetch();
if (!$food) {
    die("Food item not found.");
}

// Optional: If you want to allow deleting food even if it has orders, 
// you can remove foreign key constraint or set ON DELETE CASCADE in database.
// Otherwise, this will try to delete anyway; if database restricts, it will fail.

// Delete food
$stmt = $pdo->prepare("DELETE FROM food_items WHERE food_id=?");
$stmt->execute([$food_id]);

header("Location: foods.php");
exit();
?>