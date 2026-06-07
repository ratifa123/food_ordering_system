<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Hakikisha cart ipo
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php");
    exit();
}

// Handle update quantities
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $food_id => $qty) {
        $food_id = (int)$food_id;
        $qty = (int)$qty;
        if ($qty > 0 && isset($_SESSION['cart'][$food_id])) {
            $_SESSION['cart'][$food_id]['quantity'] = $qty;
        }
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Your Cart - Food Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --main-green: #19a463;
            --main-green-dark: #14834e;
            --main-bg: #f5f6fa;
            --main-card: #fff;
            --main-shadow: 0 6px 24px rgba(25, 164, 99, 0.2);
            --accent-color: #ff6b6b;
            --font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body { font-family: var(--font-family); margin: 0; padding: 0; background-color: var(--main-bg); color: #333; }
        .cart-container { max-width: 900px; margin: 40px auto; background-color: var(--main-card); padding: 32px 35px; border-radius: 18px; box-shadow: var(--main-shadow); }
        .cart-header { text-align: center; margin-bottom: 32px; color: var(--main-green-dark); font-size: 2.3rem; font-weight: 700; letter-spacing: 0.5px;}
        table { width: 100%; border-collapse: collapse; background: #f7fafb; border-radius: 12px; overflow: hidden;}
        table thead { background-color: var(--main-green-dark); color: #fff; }
        table th, table td { padding: 16px 15px; text-align: center; border-bottom: 1px solid #e5e5e5; }
        table th { font-weight: 600; letter-spacing: 0.5px; font-size: 1.08rem;}
        table td img { width: 80px; height: 80px; object-fit: cover; border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07); transition: transform 0.3s; border: 2px solid #eafcf3; }
        input[type="number"] { width: 60px; padding: 7px 2px; border: 1px solid #b3e2cc; border-radius: 8px; text-align: center; font-family: var(--font-family); background: #f7fafb; transition: border 0.2s; font-size: 1.05rem;}
        input[type="number"]:focus { outline: none; border-color: var(--main-green); box-shadow: 0 0 0 2px rgba(25, 164, 99, 0.15); }
        .remove-link { color: var(--accent-color); font-size: 1.3rem; width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; transition: all 0.23s; background: rgba(255, 107, 107, 0.08); border: 1.5px solid transparent;}
        .remove-link:hover, .remove-link:active { background: var(--accent-color); color: #fff; border: 1.5px solid var(--accent-color);}
        .total-section { margin-top: 36px; padding-top: 26px; border-top: 2.5px dashed #d7e7df; display: flex; flex-direction: column; align-items: flex-end; }
        .total { font-size: 2rem; font-weight: 700; color: var(--main-green-dark); margin-bottom: 24px; background: #eafcf3; padding: 14px 34px; border-radius: 14px; box-shadow: 0 2px 12px #19a46311;}
        .cart-actions { margin-top: 30px; display: flex; justify-content: flex-end; gap: 17px;}
        .button-link { background-color: var(--main-green); color: #fff; padding: 14px 32px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size:1.09rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 9px; border:none; box-shadow: 0 3px 9px #19a46322; }
        .button-link.disabled { background-color: #cccccc; cursor: not-allowed; transform: none; box-shadow: none; }
        .button-link:hover, .button-link:focus { background: var(--main-green-dark); color: #fff; text-decoration:none;}
        .quantity-control { display: inline-flex; align-items: center; border: 1.5px solid #b3e2cc; border-radius: 9px; overflow: hidden; max-width: 130px; background: #fff; box-shadow: 0 1px 5px #19a46311;}
        .quantity-control input[type="number"] { border: none; width: 50px; text-align: center; font-size: 1.06rem; padding: 6px; outline: none; -moz-appearance: textfield; background: transparent;}
        .quantity-control input[type="number"]::-webkit-outer-spin-button, .quantity-control input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .qty-btn { padding: 4px 12px; border: none; background: transparent; color: var(--main-green-dark); cursor: pointer; border-radius: 4px; font-size: 1.3rem; transition: background .13s;}
        .qty-btn:active { background: #f1f8f5; }
        @media (max-width: 1000px) {
          .cart-container { padding: 13px 2vw; }
        }
        @media (max-width: 800px) {
          .cart-container { padding: 10px 1vw; }
          table th, table td { padding: 8px 2px; font-size: 1rem;}
          .total { font-size: 1.2rem; padding:11px 10px;}
          .button-link { font-size: 0.98rem; padding: 10px 10px;}
        }
        @media (max-width: 600px) {
          .cart-container { padding: 6px 0; }
          table th, table td { padding: 8px 1px; font-size:0.93rem;}
          .total { font-size: 1rem; }
          .cart-header { font-size:1.1rem; margin-bottom:18px;}
        }
    </style>
</head>
<body>

<div class="cart-container">
    <div class="cart-header"><i class="bi bi-cart4"></i> Your Cart</div>
    <?php if (!empty($_SESSION['cart'])): ?>
    <form method="post" action="">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Food</th>
                    <th>Price (TZS)</th>
                    <th>Quantity</th>
                    <th>Subtotal (TZS)</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" title="<?= htmlspecialchars($item['name']) ?>"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price']) ?></td>
                    <td>
                        <div class="quantity-control">
                            <button type="button" class="qty-btn minus">−</button>
                            <input
                                type="number"
                                name="quantities[<?= $item['food_id'] ?>]"
                                value="<?= $item['quantity'] ?>"
                                min="1"
                                required
                                data-price="<?= $item['price'] ?>"
                            >
                            <button type="button" class="qty-btn plus">+</button>
                        </div>
                    </td>
                    <td class="subtotal"><?= number_format($subtotal) ?></td>
                    <td>
                        <a
                            class="remove-link"
                            href="cart.php?remove=<?= $item['food_id'] ?>"
                        ><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total">Total: TZS <span id="totalAmount"><?= number_format($total) ?></span></div>
            <div class="cart-actions">
                <a href="menu.php" class="button-link"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
                <a href="checkout.php" class="button-link"><i class="bi bi-bag-check"></i> Checkout</a>
            </div>
        </div>
    </form>
    <?php else: ?>
        <p style="text-align:center; font-size: 1.3rem; color: #19a463; margin-top: 40px;">
            Your cart is empty.<br>
            <a href="menu.php" class="button-link" style="margin-top:20px;"><i class="bi bi-arrow-left"></i> Go to Menu</a>
        </p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const quantityInputs = document.querySelectorAll('input[name^="quantities"]');
    const subtotalCells = document.querySelectorAll('td.subtotal');
    const totalAmountSpan = document.getElementById('totalAmount');

    function updateTotals() {
        let total = 0;
        quantityInputs.forEach((input, idx) => {
            const price = parseFloat(input.dataset.price);
            const qty = parseInt(input.value) || 1;
            const subtotal = price * qty;
            if (subtotalCells[idx]) {
                subtotalCells[idx].textContent = subtotal.toLocaleString();
            }
            total += subtotal;
        });
        totalAmountSpan.textContent = total.toLocaleString();
    }

    quantityInputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.value < 1) input.value = 1;
            updateTotals();
        });
    });

    // PLUS/MINUS kwa kila control
    document.querySelectorAll('.quantity-control').forEach(control => {
        const input = control.querySelector('input[type="number"]');
        const minusBtn = control.querySelector('.minus');
        const plusBtn = control.querySelector('.plus');

        minusBtn.addEventListener('click', () => {
            const current = parseInt(input.value) || 1;
            if (current > 1) {
                input.value = current - 1;
                input.dispatchEvent(new Event('input'));
            }
        });

        plusBtn.addEventListener('click', () => {
            const current = parseInt(input.value) || 1;
            input.value = current + 1;
            input.dispatchEvent(new Event('input'));
        });
    });

    updateTotals();
});
</script>

</body>
</html>

