<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

// Quick Stats
$total_orders      = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_delivered   = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='delivered'")->fetchColumn();
$total_pending     = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
$total_inprogress  = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='inprogress'")->fetchColumn();
$total_cancelled   = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='cancelled'")->fetchColumn();
$total_foods       = $pdo->query("SELECT COUNT(*) FROM food_items")->fetchColumn();
$total_customers   = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM orders")->fetchColumn();
$total_categories  = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_promos      = $pdo->query("SELECT COUNT(*) FROM promo_codes")->fetchColumn();

$total_sales = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status IN ('delivered','inprogress')")->fetchColumn();
$total_sales = $total_sales ? $total_sales : 0;

// Orders per day (last 7 days)
$chart_labels = [];
$chart_data = [];
for ($i=6; $i>=0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('M d', strtotime($date));
    $orders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE DATE(order_date)=?");
    $orders->execute([$date]);
    $chart_data[] = (int)$orders->fetchColumn();
}

// Orders per status for chart
$order_statuses = ['pending','inprogress','delivered','cancelled'];
$status_chart_data = [];
foreach($order_statuses as $s) {
    $q = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE status=?");
    $q->execute([$s]);
    $status_chart_data[] = (int)$q->fetchColumn();
}

// Recent Orders (last 5)
$stmt = $pdo->query("
    SELECT o.*, u.username, u.phone 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.user_id 
    ORDER BY o.order_date DESC LIMIT 5
");
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Trending Foods (top 5 by quantity)
$stmt2 = $pdo->query("
    SELECT f.name, f.image_url, SUM(oi.quantity) as qty 
    FROM order_items oi 
    LEFT JOIN food_items f ON oi.food_id = f.food_id 
    GROUP BY oi.food_id ORDER BY qty DESC LIMIT 5
");
$top_foods = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Food Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ===== General Layout ===== */
body {
    background-color: #f5f7fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    margin: 0;
    padding: 20px;
}

.dashboard-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* ===== Greeting Bar ===== */
.greet {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #14834e;
    border-bottom: 2px solid #19a463;
    padding-bottom: 10px;
}

/* ===== Quick Stats Cards ===== */
.quick-stats {
    gap: 15px;
    flex-wrap: wrap;
}

.stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    flex: 1;
    min-width: 140px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-icon {
    font-size: 1.8rem;
    color: #19a463;
    display: block;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.9rem;
    color: #555;
}

.stat-num {
    font-size: 1.3rem;
    font-weight: bold;
    color: #222;
}

.stat-sales {
    background: linear-gradient(135deg, #19a463, #14834e);
    color: #fff;
}

.stat-sales .stat-icon,
.stat-sales .stat-label,
.stat-sales .stat-num {
    color: #fff;
}

/* ===== Quick Links ===== */
.quick-links {
    gap: 10px;
    flex-wrap: wrap;
}

.quick-link {
    background: #19a463;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.3s;
}

.quick-link:hover {
    background: #14834e;
    color: #fff;
}

/* ===== Section Titles ===== */
.section-title {
    font-weight: 600;
    font-size: 1rem;
    margin: 15px 0 8px;
    color: #14834e;
    border-left: 4px solid #19a463;
    padding-left: 8px;
}

/* ===== Tables ===== */
.mini-table {
    font-size: 0.9rem;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.mini-table thead {
    background: #19a463;
    color: #fff;
}

.mini-table th, 
.mini-table td {
    padding: 8px 10px;
    vertical-align: middle;
}

.mini-table tbody tr:hover {
    background: #f2fdf8;
}

/* ===== Trending Foods Images ===== */
.trending-food-img {
    width: 35px;
    height: 35px;
    object-fit: cover;
    border-radius: 50%;
    margin-right: 6px;
    border: 2px solid #ddd;
}

/* ===== Charts Box ===== */
.chart-box {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
}
</style>
</head>
<body>
<div class="dashboard-container">
    <div class="greet">
        Welcome, <?= htmlspecialchars($_SESSION['admin_fullname']) ?>!
        <span style="float:right">
            <a href="admin_logout.php" class="btn btn-sm btn-outline-danger">Logout <i class="bi bi-box-arrow-right"></i></a>
        </span>
    </div>
    <div class="row quick-stats d-flex mb-4">
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-bag-check"></i></span>
            <div class="stat-label">Orders</div>
            <div class="stat-num"><?= $total_orders ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-truck"></i></span>
            <div class="stat-label">Delivered</div>
            <div class="stat-num"><?= $total_delivered ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-clock-history"></i></span>
            <div class="stat-label">Pending</div>
            <div class="stat-num"><?= $total_pending ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-hourglass-split"></i></span>
            <div class="stat-label">Progress</div>
            <div class="stat-num"><?= $total_inprogress ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-x-circle"></i></span>
            <div class="stat-label">Cancelled</div>
            <div class="stat-num"><?= $total_cancelled ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-egg-fried"></i></span>
            <div class="stat-label">Foods</div>
            <div class="stat-num"><?= $total_foods ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-tags"></i></span>
            <div class="stat-label">Categories</div>
            <div class="stat-num"><?= $total_categories ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-ticket-perforated"></i></span>
            <div class="stat-label">Promos</div>
            <div class="stat-num"><?= $total_promos ?></div>
        </div>
        <div class="col stat-card">
            <span class="stat-icon"><i class="bi bi-people"></i></span>
            <div class="stat-label">Customers</div>
            <div class="stat-num"><?= $total_customers ?></div>
        </div>
        <div class="col stat-card stat-sales">
            <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
            <div class="stat-label">Sales (TSh)</div>
            <div class="stat-num"><?= number_format($total_sales, 0) ?></div>
        </div>
    </div>

    <div class="d-flex quick-links mb-4">
        <a href="orders.php" class="quick-link"><i class="bi bi-list-ul"></i> Orders</a>
        <a href="foods.php" class="quick-link"><i class="bi bi-egg-fried"></i> Foods</a>
        <a href="categories.php" class="quick-link"><i class="bi bi-tags"></i> Categories</a>
        <a href="promos.php" class="quick-link"><i class="bi bi-ticket-perforated"></i> Promos</a>
        <a href="users.php" class="quick-link"><i class="bi bi-person-lines-fill"></i> Users</a>
    </div>

    <div class="row">
        <div class="col-md-8 chart-box mb-4">
            <h5 class="mb-2" style="color:#19a463; font-weight:700;">Orders (Last 7 days)</h5>
            <canvas id="ordersChart" height="90"></canvas>
            <hr>
            <h6 style="color:#14834e; font-weight:700;">Orders Per Status</h6>
            <canvas id="statusChart" height="70"></canvas>
        </div>
        <div class="col-md-4">
            <div class="section-title">Recent Orders</div>
            <table class="table table-bordered mini-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($recent_orders as $i=>$o): ?>
                    <tr>
                        <td>OD<?=str_pad($o['order_id'],6,"0",STR_PAD_LEFT)?></td>
                        <td><?=htmlspecialchars($o['username'])?><br><span style="font-size:.87em;color:#888;"><?=htmlspecialchars($o['phone'])?></span></td>
                        <td>
                            <?php
                            $c = "badge ";
                            if($o['status']=="pending") $c.="bg-warning text-dark";
                            elseif($o['status']=="inprogress") $c.="bg-info text-dark";
                            elseif($o['status']=="delivered") $c.="bg-success";
                            elseif($o['status']=="cancelled") $c.="bg-danger";
                            else $c.="bg-secondary";
                            echo "<span class='$c'>".ucfirst($o['status'])."</span>";
                            ?>
                        </td>
                        <td><?=number_format($o['total_amount'],0)?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>

            <div class="section-title">Trending Foods</div>
            <table class="table table-bordered mini-table">
                <thead>
                    <tr>
                        <th>Food</th>
                        <th>Sold</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($top_foods as $f): ?>
                    <tr>
                        <td>
                            <?php if($f['image_url']): ?>
                                <img src="<?=htmlspecialchars($f['image_url'])?>" class="trending-food-img">
                            <?php endif; ?>
                            <?=htmlspecialchars($f['name'])?>
                        </td>
                        <td><?=intval($f['qty'])?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Orders',
            data: <?= json_encode($chart_data) ?>,
            borderColor: '#19a463',
            backgroundColor: 'rgba(25,164,99,0.13)',
            tension: 0.4,
            fill: true,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointBackgroundColor: '#19a463'
        }]
    },
    options: {
        responsive:true,
        plugins: { legend:{display:false} },
        scales: {
            y: { beginAtZero:true, ticks:{stepSize:1, color:'#14834e'} },
            x: { ticks:{color:'#14834e'} }
        }
    }
});

// Orders per status chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map('ucfirst', $order_statuses)) ?>,
        datasets: [{
            label: 'Orders',
            data: <?= json_encode($status_chart_data) ?>,
            backgroundColor: [
                '#ffe49e', '#b0d4f7', '#d4ffe4', '#fbbcb1'
            ],
            borderColor: [
                '#e6aa1c', '#2c91e2', '#19a463', '#d05943'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive:true,
        plugins: { legend:{display:false} },
        scales: {
            y: { beginAtZero:true, ticks:{stepSize:1, color:'#14834e'} },
            x: { ticks:{color:'#14834e'} }
        }
    }
});
</script>
</body>
</html>