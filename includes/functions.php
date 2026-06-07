<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit;
}

// Check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Sanitize user input
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

?>
