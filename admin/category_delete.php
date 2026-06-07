<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

$food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($food_id <= 0) { die("Invalid food id"); }

// Angalia kama chakula kipo
$stmt = $pdo->prepare("SELECT * FROM food_items WHERE food_id=?");
$stmt->execute([$food_id]);
$food = $stmt->fetch();
if (!$food) {
    die("Food item not found.");
}

// Futa chakula
$stmt = $pdo->prepare("DELETE FROM food_items WHERE food_id=?");
$stmt->execute([$food_id]);
header("Location: foods.php");
exit();
?>