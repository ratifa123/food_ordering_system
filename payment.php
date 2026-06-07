<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['last_order_id'])) {
    $_SESSION['message'] = "No order found to make payment for.";
    header("Location: menu.php");
    exit();
}

$username = $_SESSION['username'];
$order_id = $_SESSION['last_order_id'];
$errors = [];
$success_message = '';

// Pata user_id kutoka DB
$stmtUser = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
$stmtUser->execute([$username]);
$user = $stmtUser->fetch();

if (!$user) {
    $errors[] = "User not found.";
}

// Pata total amount
$stmtOrderTotal = $pdo->prepare("
    SELECT SUM(fi.price * oi.quantity) AS total_amount
    FROM order_items oi
    JOIN food_items fi ON oi.food_id = fi.food_id
    WHERE oi.order_id = ?
");
$stmtOrderTotal->execute([$order_id]);
$orderData = $stmtOrderTotal->fetch();
$order_total = $orderData ? $orderData['total_amount'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    $payment_account = trim($_POST['payment_account'] ?? '');
    $amount = $_POST['amount'] ?? '';

    $valid_methods = ['Tigo Pesa', 'Airtel Money', 'Paypal', 'Bank Transfer'];
    if (!in_array($payment_method, $valid_methods)) {
        $errors[] = "Please select a valid payment method.";
    }

    if (!is_numeric($amount) || $amount <= 0) {
        $errors[] = "Please enter a valid payment amount.";
    } elseif ($amount > $order_total) {
        $errors[] = "Payment amount cannot exceed total order amount.";
    }

    if (empty($payment_account)) {
        $errors[] = "Please enter your payment account or number.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO payments (user_id, amount, payment_time, payment_type, payment_status, payment_method) VALUES (?, ?, NOW(), ?, ?, ?)");
            $stmt->execute([$user['user_id'], $amount, 'Now', 'paid', $payment_method]);

            $stmtOrder = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
            $stmtOrder->execute(['paid', $order_id]);

            $message = "Payment successful for order #$order_id.";
            $stmtNotif = $pdo->prepare("INSERT INTO notifications (user_id, message, timestamp) VALUES (?, ?, NOW())");
            $stmtNotif->execute([$user['user_id'], $message]);

            $success_message = "Payment processed successfully!";
            unset($_SESSION['last_order_id']);
        } catch (Exception $e) {
            $errors[] = "Failed to process payment. Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morogoro Taste Food - Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style id="app-style">
        :root {
            --main-green: #19a463;
            --main-green-dark: #14834e;
            --main-bg: #f5f6fa;
            --main-card: #fff;
            --main-shadow: 0 6px 24px #19a46313;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 15px;
        }

        .title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--main-green);
            margin-bottom: 10px;
        }

        .subtitle {
            color: var(--main-green-dark);
            font-size: 1.1rem;
        }

        .payment-card {
            background: var(--main-card);
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: var(--main-shadow);
            max-width: 500px;
            width: 100%;
        }

        .order-total {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--main-green-dark);
        }

        .message-banner {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
        }

        .error-banner {
            background: var(--error-bg);
            color: var(--error-red);
        }

        .success-banner {
            background: var(--success-bg);
            color: var(--success-green);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .input-field {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #c3e6cb;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: var(--main-green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 164, 99, 0.15);
        }

        .input-error {
            border-color: var(--error-red);
        }

        .error-text {
            color: var(--error-red);
            font-size: 0.85rem;
            margin-top: 5px;
            font-weight: 500;
        }

        .submit-btn {
            display: block;
            width: 80%;
            margin: 30px auto 0;
            background: var(--main-green);
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 14px 20px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(25, 164, 99, 0.3);
        }

        .submit-btn:hover {
            background: var(--main-green-dark);
            transform: scale(1.05);
        }

        .submit-btn:disabled {
            background: #93c5aa;
            cursor: not-allowed;
            transform: scale(1);
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--main-green);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .payment-card {
                padding: 25px 20px;
            }

            .title {
                font-size: 1.5rem;
            }

            .order-total {
                font-size: 1.5rem;
            }

            .submit-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
       
        <h1 class="title">Morogoro Taste Food</h1>
        <p class="subtitle">Payment</p>
    </div>

    <div class="payment-card">
        <div class="order-total" id="order-total">Total: TZS 0.00</div>
        
        <div id="message-container" class="hidden"></div>
        
        <form id="payment-form">
            <div class="form-group">
                <label for="amount">Amount (TZS)</label>
                <input type="number" id="amount" name="amount" class="input-field" required min="1">
                <div class="error-text" id="amount-error"></div>
            </div>
            
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method" class="input-field" required>
                    <option value="">-- Select Payment Method --</option>
                    <option value="Tigo Pesa">Tigo Pesa</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="Airtel Money">M-PESA</option>
                    <option value="Airtel Money">Halo Pesa</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
                <div class="error-text" id="payment-method-error"></div>
            </div>
            
            <div class="form-group">
                <label for="payment_account">Payment Account/Number</label>
                <input type="text" id="payment_account" name="payment_account" class="input-field" placeholder="Enter your payment account or phone number" required>
                <div class="error-text" id="payment-account-error"></div>
            </div>
            
            <button type="submit" id="submit-btn" class="submit-btn">Submit Payment</button>
        </form>
        
        <div id="success-actions" style="display: none; text-align: center; margin-top: 20px;">
            <a href="menu.php" class="back-link">Back to Menu</a>
        </div>
    </div>

    <script id="app-script">
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const orderTotalDisplay = document.getElementById('order-total');
            const messageContainer = document.getElementById('message-container');
            const paymentForm = document.getElementById('payment-form');
            const amountInput = document.getElementById('amount');
            const paymentMethodSelect = document.getElementById('payment_method');
            const paymentAccountInput = document.getElementById('payment_account');
            const submitBtn = document.getElementById('submit-btn');
            const successActions = document.getElementById('success-actions');
            
            // Error message elements
            const amountError = document.getElementById('amount-error');
            const paymentMethodError = document.getElementById('payment-method-error');
            const paymentAccountError = document.getElementById('payment-account-error');
            
            // Fetch order details on page load
            fetchOrderDetails();
            
            // Add form validation
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateForm()) {
                    processPayment();
                }
            });
            
            // Real-time validation
            amountInput.addEventListener('input', validateAmount);
            paymentMethodSelect.addEventListener('change', validatePaymentMethod);
            paymentAccountInput.addEventListener('input', validatePaymentAccount);
            
            // Form validation functions
            function validateForm() {
                let isValid = true;
                
                if (!validateAmount()) isValid = false;
                if (!validatePaymentMethod()) isValid = false;
                if (!validatePaymentAccount()) isValid = false;
                
                return isValid;
            }
            
            function validateAmount() {
                const value = amountInput.value.trim();
                if (!value) {
                    showError(amountInput, amountError, 'Amount is required');
                    return false;
                } else if (isNaN(value) || Number(value) <= 0) {
                    showError(amountInput, amountError, 'Please enter a valid amount');
                    return false;
                } else {
                    clearError(amountInput, amountError);
                    return true;
                }
            }
            
            function validatePaymentMethod() {
                if (!paymentMethodSelect.value) {
                    showError(paymentMethodSelect, paymentMethodError, 'Please select a payment method');
                    return false;
                } else {
                    clearError(paymentMethodSelect, paymentMethodError);
                    return true;
                }
            }
            
            function validatePaymentAccount() {
                const value = paymentAccountInput.value.trim();
                if (!value) {
                    showError(paymentAccountInput, paymentAccountError, 'Payment account/number is required');
                    return false;
                } else {
                    clearError(paymentAccountInput, paymentAccountError);
                    return true;
                }
            }
            
            function showError(input, errorElement, message) {
                input.classList.add('input-error');
                errorElement.textContent = message;
            }
            
            function clearError(input, errorElement) {
                input.classList.remove('input-error');
                errorElement.textContent = '';
            }
            
            // API call functions (Loops)
            function fetchOrderDetails() {
                // Simulate API call to get order details
                setTimeout(() => {
                    // Mock data for prototype
                    const orderData = {
                        order_id: 12345,
                        order_total: 45000
                    };
                    
                    // Update UI with order data
                    orderTotalDisplay.textContent = `Total: TZS ${formatNumber(orderData.order_total)}`;
                    amountInput.value = orderData.order_total;
                }, 500);
            }
            
            function processPayment() {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                
                // Simulate API call for payment processing
                setTimeout(() => {
                    const success = true; // For prototype, always succeed
                    
                    if (success) {
                        showMessage('success', 'Payment processed successfully!');
                        paymentForm.style.display = 'none';
                        successActions.style.display = 'block';
                    } else {
                        showMessage('error', 'Failed to process payment. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Payment';
                    }
                }, 2000);
            }
            
            // Helper functions
            function showMessage(type, message) {
                messageContainer.className = 'message-banner';
                messageContainer.classList.add(type === 'error' ? 'error-banner' : 'success-banner');
                messageContainer.textContent = message;
                messageContainer.style.display = 'block';
                
                // Scroll to message if needed
                messageContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            
            function formatNumber(num) {
                return new Intl.NumberFormat('en-US').format(num);
            }
        });
    </script>
</body>
</html>