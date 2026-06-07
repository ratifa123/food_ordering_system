<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['id'])) {
    $food_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM food_items WHERE food_id = ?");
    $stmt->execute([$food_id]);
    $food = $stmt->fetch();

    if ($food) {
        if (isset($_SESSION['cart'][$food_id])) {
            $_SESSION['cart'][$food_id]['quantity']++;
        } else {
            $_SESSION['cart'][$food_id] = [
                'food_id' => $food['food_id'], 
                'name' => $food['name'],
                'price' => $food['price'],
                'image' => $food['image_url'],
                'quantity' => 1,
            ];
        }
        $_SESSION['message'] = $food['name'] . " added to cart!";
    } else {
        $_SESSION['message'] = "Food item not found.";
    }
} else {
    $_SESSION['message'] = "No food item specified.";
}

header("Location: menu.php");
exit();