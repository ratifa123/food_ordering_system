<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch categories from the categories table
$categories = [];
$stmtCat = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
while ($row = $stmtCat->fetch()) {
    $categories[] = $row;
}

// All foods for main menu and search
$stmt = $pdo->query("SELECT * FROM food_items");
$all_foods = $stmt->fetchAll();
$current_category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

$search_not_found = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = trim($_GET['search']);
    $foods = array_filter($all_foods, function($food) use ($search, $current_category_id) {
        $match = stripos($food['name'], $search) !== false;
        if ($current_category_id > 0) {
            $match = $match && $food['category_id'] == $current_category_id;
        }
        return $match;
    });
    if (empty($foods)) $search_not_found = "No food found matching your search.";
} else if ($current_category_id > 0) {
    $foods = array_filter($all_foods, function($food) use ($current_category_id) {
        return $food['category_id'] == $current_category_id;
    });
    if (empty($foods)) $search_not_found = "No food in this category.";
} else {
    $foods = $all_foods;
}

// For cart badge
$cart_count = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Prepare foods for JS search suggestion
$foods_json = [];
foreach ($all_foods as $food) {
    $foods_json[] = [
        'name' => $food['name'],
        'image_url' => $food['image_url'],
        'category_id' => $food['category_id']
    ];
}

// Detect if only one food is being displayed due to search
$single_search_result = false;
if (isset($_GET['search']) && $_GET['search'] !== '' && count($foods) === 1) {
    $single_search_result = true;
}

// Helper: get category name for food
function categoryName($id, $categories){
    foreach($categories as $cat){
        if($cat['category_id'] == $id) return $cat['category_name'];
    }
    return 'Unknown';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Morogoro Taste Food | Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
      <style>
        :root {
            --main-green: #19a463;
            --main-green-dark: #14834e;
            --main-bg: #f5f6fa;
            --main-card: #fff;
            --main-shadow: 0 6px 24px #19a46313;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--main-bg);
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        /* --- TOPBAR --- */
        .topbar {
            width: 100%;
            display: flex;
            align-items: stretch;
            background: #fff;
            border-bottom: 1.5px solid #eaeaea;
            box-sizing: border-box;
            position: sticky;
            top: 0;
            z-index: 10;
            height: 66px;
            padding: 0;
        }
        .topbar-inner {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            align-items: center;
            height: 100%;
        }
        .topbar-left,
        .topbar-center,
        .topbar-right {
            display: flex;
            align-items: center;
            height: 100%;
        }
        .topbar-left {
            justify-content: flex-start;
            padding-left: 35px;
        }
        .topbar-center {
            justify-content: center;
            min-width: 0;
        }
        .topbar-right {
            justify-content: flex-end;
            gap: 16px;
            padding-right: 35px;
        }
        .site-title {
            font-size: 1.45rem;
            font-weight: 900;
            color: var(--main-green);
            letter-spacing: 1.1px;
            background: linear-gradient(90deg, #19a463 60%, #0b3c49 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px #19a46317;
            user-select: none;
            margin-right: 16px;
        }
        /* SEARCH */
        .search-form-row {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            min-width: 0;
        }
        .search-suggest-box {
            position: relative;
            width: 260px;
            max-width: 90vw;
        }
        .search-suggest-box input[type="text"] {
            padding: 10px 16px;
            width: 100%;
            border: 2px solid var(--main-green);
            border-radius: 20px;
            font-size: 1.02rem;
            color: #1a1a1a;
            background: #fff;
            font-weight: 500;
            outline: none;
            box-sizing: border-box;
            box-shadow: 0 2px 10px #19a46315;
            transition: border .2s, box-shadow 0.2s;
        }
        .search-suggest-box input[type="text"]:focus {
            border: 2px solid var(--main-green-dark);
            box-shadow: 0 4px 16px #19a46322;
        }
        .search-suggest-results {
            position: absolute;
            left: 0; right: 0;
            background: #fff;
            border: 2px solid var(--main-green);
            border-top: none;
            border-radius: 0 0 14px 14px;
            max-height: 160px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 10px 24px #19a46322;
            display: none;
        }
        .search-suggest-results.active {
            display: block;
        }
        .search-suggest-item {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px 8px 6px;
            cursor: pointer;
            font-size: 0.97rem;
            color: #222;
            border-bottom: 1px solid #f5f5f5;
            background: #fff;
            transition: background 0.13s;
        }
        .search-suggest-item:last-child {
            border-bottom: none;
        }
        .search-suggest-item:hover, .search-suggest-item.active {
            background: #e8fff3;
            color: var(--main-green);
        }
        .search-suggest-item .suggest-thumb {
            width: 11px !important;
            height: 9px !important;
            min-width: 11px !important;
            min-height: 9px !important;
            max-width: 11px !important;
            max-height: 9px !important;
            object-fit: cover;
            border-radius: 2px;
            background: #eee;
            border: 1px solid #f1f1f1;
            margin-right: 5px;
            display: inline-block;
        }
        .search-suggest-btn {
            padding: 8px 15px;
            border: none;
            background: var(--main-green);
            color: #fff;
            font-weight: 700;
            border-radius: 19px;
            cursor: pointer;
            font-size: 1.02rem;
            margin-left: 3px;
            margin-right: 0;
            transition: background 0.2s;
            box-shadow: 0 2px 8px #19a46322;
        }
        .search-suggest-btn:hover {
            background: var(--main-green-dark);
        }
        .clear-link {
            margin-left: 7px;
            color: var(--main-green-dark);
            font-size: 0.97rem;
            text-decoration: underline;
            font-weight: 500;
        }
        /* ABOUT US BUTTON - SMALL */
        .about-link {
            color: var(--main-green);
            font-size: 0.93rem;
            font-weight: 500;
            background: #e8fff3;
            border-radius: 16px;
            padding: 6px 13px;
            border: 1px solid var(--main-green);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 2px 7px #19a46313;
            transition: background 0.16s, color 0.16s, border 0.16s;
            position: relative;
        }
        .about-link i {
            color: var(--main-green);
            font-size: 1.02em;
        }
        .about-link:hover {
            background: var(--main-green);
            color: #fff;
            border-color: var(--main-green-dark);
        }
        .about-link:hover i {
            color: #fff;
        }
        /* CART */
        .cart-link {
            color: #fff;
            background: linear-gradient(90deg,#19a463 60%,#0b3c49 100%);
            text-decoration: none;
            padding: 9px 19px 9px 13px;
            border-radius: 20px;
            font-size: 1.08rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 16px #19a46322;
            border: none;
            position: relative;
            transition: background 0.18s, box-shadow 0.18s;
        }
        .cart-link i {
            margin-right: 7px;
            font-size: 1.23em;
            color: #fffbe0;
            filter: drop-shadow(0 1px 1px #0002);
        }
        .cart-link:hover {
            background: linear-gradient(90deg,#14834e 50%,#0a2a34 100%);
            box-shadow: 0 8px 24px #19a46344;
        }
        .cart-badge {
            position: absolute;
            top: 3px;
            right: 10px;
            background: #ff4141;
            color: #fff;
            font-size: 0.75rem;
            min-width: 15px;
            height: 15px;
            border-radius: 10px;
            padding: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            pointer-events: none;
            z-index: 2;
            border: 1px solid #fff;
            box-shadow: 0 2px 6px #ff414133;
        }
        .logout-link {
            color: #fff;
            background: #d9534f;
            text-decoration: none;
            padding: 9px 13px;
            border-radius: 15px;
            font-size: 0.93rem;
            margin-left: 6px;
            display: inline-block;
            font-weight: 500;
            transition: background 0.18s;
        }
        .logout-link:hover {
            background: #b52c28;
        }
        /* CATEGORIES */
        .category-bar {
            background: #f8f8fa;
            padding: 10px 30px 9px 30px;
            border-bottom: 1.5px solid #eaeaea;
            display: flex;
            gap: 13px;
            flex-wrap: wrap;
        }
        .category-btn {
            background: #fff;
            border: 1.5px solid var(--main-green);
            color: var(--main-green);
            font-weight: 600;
            border-radius: 16px;
            padding: 7px 16px;
            font-size: 0.97rem;
            margin-bottom: 2px;
            cursor: pointer;
            transition: background 0.18s, color 0.18s, border 0.18s;
            text-decoration: none;
        }
        .category-btn.selected,
        .category-btn:hover {
            background: var(--main-green);
            color: #fff;
        }
        /* NOTIFICATIONS */
        .notification {
            max-width: 410px;
            margin: 0 auto 20px auto;
            padding: 11px 16px;
            background: var(--main-green);
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            text-align: center;
            word-break: break-word;
            overflow-wrap: break-word;
            opacity: 1;
            box-shadow: 0 2px 10px #19a46333;
            transition: opacity 0.8s;
        }
        .hide-notification {
            opacity: 0 !important;
            transition: opacity 0.8s;
        }
        /* MENU CARDS */
        .menu-container {
            max-width: 1200px;
            margin: 0 auto 54px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
            gap: 12px 8px;
            padding: 0 4px;
            justify-content: center;
        }
        .menu-item {
            margin: 0;
            background: var(--main-card);
            border-radius: 10px;
            box-shadow: var(--main-shadow);
            border: 1.5px solid #eaeaea;
            padding: 0 0 10px 0;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            transition: box-shadow 0.2s, border 0.2s;
            height: 100%;
        }
        .menu-item:hover {
            box-shadow: 0 12px 32px #19a46322;
            border: 1.5px solid var(--main-green);
        }
        .menu-item img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            display: block;
            margin: 0;
            background: #eaeaea;
            transition: height 0.2s, max-width 0.2s;
        }
        /* If only one food is displayed due to search: make image smaller */
        .single-search-result .menu-item img {
            height: 80px !important;
            max-width: 180px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            border-radius: 10px 10px 10px 10px;
        }
        .single-search-result .menu-item {
            max-width: 220px;
            margin: 0 auto;
        }
        .menu-item-content {
            padding: 8px 8px 0 8px;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }
        .menu-item h3 {
            margin: 0 0 7px 0;
            font-weight: 700;
            font-size: 1.07rem;
            color: var(--main-green);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        .menu-item p {
            font-size: 0.99rem;
            color: #444;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        .price {
            font-weight: 700;
            color: #0b3c49;
            font-size: 1.02rem;
            margin-bottom: 7px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        .add-to-cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 19px;
            background: var(--main-green);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 1.01rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
            box-shadow: 0 2px 8px #19a46322;
            transition: background 0.18s, box-shadow 0.18s;
            align-self: flex-start;
        }
        .add-to-cart-btn i {
            font-size: 1.15em;
            vertical-align: middle;
        }
        .add-to-cart-btn span {
            display: none;
        }
        .add-to-cart-btn:hover {
            background: var(--main-green-dark);
            box-shadow: 0 8px 16px #19a46333;
        }
        /* ABOUT MODAL */
        .about-modal-bg {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0; top: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.13);
        }
        .about-modal-bg.active { display: block; }
        .about-modal {
            background: #fff;
            width: 95vw;
            max-width: 320px;
            margin: 12vh auto 0 auto;
            border-radius: 14px;
            box-shadow: 0 6px 32px #2222;
            padding: 25px 19px 17px 21px;
            text-align: left;
            position: relative;
            animation: popIn 0.19s cubic-bezier(.21,1.01,.53,.97);
        }
        .about-modal h2 {
            margin: 0 0 10px 0;
            color: var(--main-green);
            font-size: 1.07rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .about-modal h2 i {
            color: var(--main-green);
            font-size: 1.1em;
        }
        .about-modal p {
            color: #333;
            font-size: 0.98rem;
            line-height: 1.5;
            margin: 7px 0 0 0;
        }
        .about-modal .abt-highlight {
            color: var(--main-green);
            font-weight: 500;
        }
        .about-modal ul {
            margin: 10px 0 0 18px;
            color: #444;
            font-size: 0.97rem;
        }
        .about-modal .close-abt {
            position: absolute;
            right: 11px;
            top: 10px;
            font-size: 1.15rem;
            color: #aaa;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.18s;
        }
        .about-modal .close-abt:hover {
            color: var(--main-green);
        }
        @media (max-width: 900px) {
            .topbar-left { padding-left: 13px;}
            .topbar-right { padding-right: 13px;}
            .category-bar { padding-left: 10px; padding-right: 10px;}
        }
        @media (max-width: 600px) {
            .topbar-inner {
                grid-template-columns: 1fr;
                grid-auto-rows: auto;
                gap: 0;
            }
            .topbar {
                height: auto;
            }
            .topbar-left, .topbar-center, .topbar-right {
                justify-content: center;
                padding: 9px 0;
            }
            .site-title {
                margin-left: 0;
                font-size: 1.05rem;
            }
            .category-bar {
                padding-left: 3vw;
                padding-right: 3vw;
            }
            .about-modal {
                padding: 16px 5vw 11px 5vw;
            }
            .menu-item, .menu-item img {
                max-width: 98vw;
            }
            .single-search-result .menu-item {
                max-width: 96vw;
            }
            .single-search-result .menu-item img {
                max-width: 80vw;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
  <div class="topbar-inner">
    <div class="topbar-left">
      <span class="site-title">Morogoro Taste Food</span>
    </div>
    <div class="topbar-center">
      <form method="GET" action="menu.php" id="search-form" class="search-form-row">
        <div class="search-suggest-box">
          <input type="text" id="live-search" name="search" placeholder="Search food..." autocomplete="off" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
          <div class="search-suggest-results" id="search-suggest-results"></div>
        </div>
        <button type="submit" class="search-suggest-btn">Search</button>
        <?php if (!empty($_GET['search'])): ?>
          <a href="menu.php" class="clear-link">Clear</a>
        <?php endif; ?>
      </form>
    </div>
    <div class="topbar-right">
      <a href="#" class="about-link" id="about-btn"><i class="fa-solid fa-circle-info"></i> About</a>
      <a href="cart.php" class="cart-link" title="Cart">
        <i class="fa-solid fa-cart-shopping"></i>
        Cart
        <?php if ($cart_count > 0): ?>
            <span class="cart-badge"><?= $cart_count ?></span>
        <?php endif; ?>
      </a>
      <?php if (isset($_SESSION['username'])): ?>
        <a href="logout.php" class="logout-link">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="category-bar">
    <a href="menu.php" class="category-btn<?= $current_category_id == 0 ? ' selected' : '' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
        <a href="menu.php?category_id=<?= $cat['category_id'] ?>" class="category-btn<?= $current_category_id == $cat['category_id'] ? ' selected' : '' ?>"><?= htmlspecialchars($cat['category_name']) ?></a>
    <?php endforeach; ?>
</div>

<!-- About Modal -->
<div class="about-modal-bg" id="about-modal-bg">
    <div class="about-modal">
        <button class="close-abt" id="close-abt" title="Close">&times;</button>
        <h2><i class="fa-solid fa-circle-info"></i> About</h2>
        <p>
            <span class="abt-highlight">Morogoro Taste Food</span> your go-to destination for fresh, delicious meals in Morogoro. We serve authentic local favorites and international dishes, prepared with quality ingredients and delivered fast. 
            We have something for everyone at affordable prices. Enjoy convenient ordering through our app with flexible payment options – M-Pesa, Tigo Pesa, and Airtel Money.
Our Promise: Fresh food, quick service, and customer satisfaction.
Morogoro Taste Food – Great Taste, Every Time!
        </p>
    </div>
</div>

<?php if (!empty($search_not_found)): ?>
    <div class="notification" id="notification2"><?= htmlspecialchars($search_not_found) ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['message'])): ?>
    <div class="notification" id="notification3">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="menu-container<?= $single_search_result ? ' single-search-result' : '' ?>" id="menu-container">
    <?php foreach ($foods as $food): ?>
        <div class="menu-item" data-name="<?= strtolower(htmlspecialchars($food['name'])) ?>">
            <img src="<?= htmlspecialchars($food['image_url']) ?>" alt="<?= htmlspecialchars($food['name']) ?>">
            <div class="menu-item-content">
                <h3 title="<?= htmlspecialchars($food['name']) ?>"><?= htmlspecialchars($food['name']) ?></h3>
                <p title="<?= categoryName($food['category_id'], $categories) ?>">Category: <?= categoryName($food['category_id'], $categories) ?></p>
                <p class="price" title="TZS <?= number_format($food['price'], 0) ?>">TZS <?= number_format($food['price'], 0) ?></p>
                <a href="add_to_cart.php?id=<?= $food['food_id'] ?>" class="add-to-cart-btn" title="Add to Cart">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Add</span>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
  // About Modal
  document.getElementById('about-btn').onclick = function(e) {
    e.preventDefault();
    document.getElementById('about-modal-bg').classList.add('active');
  };
  document.getElementById('close-abt').onclick = function() {
    document.getElementById('about-modal-bg').classList.remove('active');
  };
  document.getElementById('about-modal-bg').onclick = function(e) {
    if (e.target === this) this.classList.remove('active');
  };

  // Notification fade
  document.addEventListener('DOMContentLoaded', function () {
    ['notification','notification2','notification3'].forEach(function(id){
      var notif = document.getElementById(id);
      if(notif){
        setTimeout(function(){
          notif.classList.add('hide-notification');
          setTimeout(function(){
            notif.style.display = 'none';
          }, 800);
        }, 3000);
      }
    });

    // Live search with thumbnails
    const foodsData = <?php echo json_encode($foods_json); ?>;
    const searchInput = document.getElementById('live-search');
    const suggestBox = document.getElementById('search-suggest-results');
    const menuContainer = document.getElementById('menu-container');
    const allMenuCards = Array.from(menuContainer.querySelectorAll('.menu-item'));

    searchInput.addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        // Live filter cards
        allMenuCards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            if (!q || name.includes(q)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        // Show suggestions with image thumbnail (small, left)
        if (q) {
            let filtered = foodsData.filter(food => food.name.toLowerCase().includes(q));
            const seen = {};
            filtered = filtered.filter(food => {
                if (seen[food.name.toLowerCase()]) return false;
                seen[food.name.toLowerCase()] = true;
                return true;
            });
            suggestBox.innerHTML = '';
            if (filtered.length > 0) {
                filtered.slice(0, 6).forEach(food => {
                    const div = document.createElement('div');
                    div.className = 'search-suggest-item';
                    const img = document.createElement('img');
                    img.className = 'suggest-thumb';
                    img.src = food.image_url;
                    img.alt = food.name;
                    div.appendChild(img);

                    const nameDiv = document.createElement('span');
                    nameDiv.textContent = food.name;
                    div.appendChild(nameDiv);

                    div.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        searchInput.value = food.name;
                        suggestBox.classList.remove('active');
                        // Show only this card
                        allMenuCards.forEach(card => {
                            if (card.querySelector('h3').textContent.toLowerCase() === food.name.toLowerCase()) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                    suggestBox.appendChild(div);
                });
                suggestBox.classList.add('active');
            } else {
                suggestBox.innerHTML = '<div class="search-suggest-item" style="color:#bbb;">No results</div>';
                suggestBox.classList.add('active');
            }
        } else {
            suggestBox.classList.remove('active');
            allMenuCards.forEach(card => card.style.display = '');
        }
    });
    searchInput.addEventListener('focus', function() {
        if (this.value.trim()) suggestBox.classList.add('active');
    });
    searchInput.addEventListener('blur', function() {
        setTimeout(()=>suggestBox.classList.remove('active'), 120);
    });
  });
</script>
</body>
</html>