<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

// Handle bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_selected') {
    $selected_ids = $_POST['selected_ids'] ?? [];
    
    if (!empty($selected_ids)) {
        try {
            foreach ($selected_ids as $order_id) {
                // Delete order items first
                $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
                $stmt->execute([$order_id]);
                
                // Delete order
                $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
                $stmt->execute([$order_id]);
            }
            $delete_message = "✅ " . count($selected_ids) . " order(s) deleted successfully!";
        } catch (PDOException $e) {
            $delete_error = "❌ Error deleting orders: " . $e->getMessage();
        }
    }
}

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT o.*, u.username, u.phone, u.email FROM orders o 
        LEFT JOIN users u ON o.user_id = u.user_id";
$params = [];

if ($search !== '') {
    $sql .= " WHERE u.username LIKE ? OR u.phone LIKE ? OR u.email LIKE ? OR o.order_id LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
}
$sql .= " ORDER BY o.order_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function badge($status) {
    $cls = "badge ";
    if($status=="pending") $cls.="bg-warning text-dark";
    elseif($status=="inprogress") $cls.="bg-info text-dark";
    elseif($status=="delivered") $cls.="bg-success";
    elseif($status=="cancelled") $cls.="bg-danger";
    else $cls.="bg-secondary";
    return "<span class='$cls'>".ucfirst($status)."</span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f7fafb; }
        .orders-card {
            background: #fff;
            box-shadow: 0 3px 18px #19a46318;
            border-radius: 16px;
            padding: 2.3rem 1.2rem;
            margin: 40px auto;
            max-width: 1200px;
        }
        .order-row { border-bottom: 1.5px solid #e9ecef; }
        .order-status { font-weight: 600; }
        .table th, .table td { vertical-align: middle; }
        .section-title { font-weight: 700; color: #19a463; margin-bottom: .8rem; font-size:1.22rem;}
        .search-box { max-width: 400px; }
        .dashboard-link {
            display: inline-block;
            margin-bottom: 18px;
            font-weight: 500;
            color: #11b981;
            background: #eafcf3;
            padding: .6em 1.3em;
            border-radius: 11px;
            text-decoration: none;
            transition: .17s;
        }
        .dashboard-link:hover {
            background: #11b981;
            color: #fff;
            text-decoration: underline;
        }
        .bulk-actions {
            display: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #19a463;
        }
        .bulk-actions.show {
            display: block;
        }
        .bulk-actions button {
            margin-right: 0.5rem;
        }
        .message-alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            animation: slideIn 0.3s ease-in-out;
        }
        .message-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }
        .message-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .checkbox-cell {
            text-align: center;
            width: 50px;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
        .fade-out {
            animation: fadeOut 0.5s ease-in-out forwards;
        }
    </style>
</head>
<body>
<div class="orders-card">
    <a href="dashboard.php" class="dashboard-link"><i class="bi bi-house"></i> Back to Dashboard</a>
    <div class="section-title">Manage Orders</div>

    <?php if (isset($delete_message)): ?>
        <div class="message-alert message-success" id="notification"><?= htmlspecialchars($delete_message) ?></div>
    <?php endif; ?>
    
    <?php if (isset($delete_error)): ?>
        <div class="message-alert message-error" id="notification"><?= htmlspecialchars($delete_error) ?></div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <form class="search-box" method="get" style="display: inline-block;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, phone, email, order no" value="<?=htmlspecialchars($search)?>">
                    <button class="btn btn-success" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulk-actions">
        <form method="POST" id="bulk-form" onsubmit="return confirm('Delete selected orders?');">
            <input type="hidden" name="action" value="delete_selected">
            <div>
                <span id="selected-count">0 orders selected</span>
                <button type="submit" class="btn btn-sm btn-danger ms-2">
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
                <button type="button" class="btn btn-sm btn-secondary ms-2" onclick="uncheckAll()">
                    Clear Selection
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
    <table class="table align-middle table-bordered" id="orders-table">
        <thead class="table-success">
            <tr>
                <th class="checkbox-cell">
                    <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                </th>
                <th>Order #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Total (TSh)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="orders-body">
        <?php if (count($orders) > 0): ?>
            <?php foreach($orders as $o): ?>
                <tr class="order-row">
                    <td class="checkbox-cell">
                        <input type="checkbox" class="order-checkbox" value="<?=$o['order_id']?>" onchange="updateBulkActions()">
                    </td>
                    <td>OD<?=str_pad($o['order_id'],6,"0",STR_PAD_LEFT)?></td>
                    <td><?=date('d M Y H:i', strtotime($o['order_date']))?></td>
                    <td><?=htmlspecialchars($o['username'])?></td>
                    <td><?=htmlspecialchars($o['phone'])?></td>
                    <td><?=badge($o['status'])?></td>
                    <td><?=number_format($o['total_amount'],0)?></td>
                    <td>
                        <a href="order_view.php?id=<?=$o['order_id']?>" class="btn btn-sm btn-success" title="View Order">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="order_status.php?id=<?=$o['order_id']?>" class="btn btn-sm btn-warning" title="Change Status">
                            <i class="bi bi-pencil"></i> Status
                        </a>
                        <a href="order_delete.php?id=<?=$o['order_id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')" title="Delete Order">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                    No orders found
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<script>
// Notification fade out after 4 seconds
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(function() {
            notification.classList.add('fade-out');
            setTimeout(function() {
                notification.style.display = 'none';
            }, 500);
        }, 4000);
    }
});

// Select all checkbox
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActions();
}

// Uncheck all
function uncheckAll() {
    document.getElementById('select-all').checked = false;
    document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = false);
    updateBulkActions();
}

// Update bulk actions display
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');
    
    const count = checkboxes.length;
    selectedCount.textContent = count + (count === 1 ? ' order selected' : ' orders selected');
    
    if (count > 0) {
        bulkActions.classList.add('show');
        // Clear previous checkboxes
        bulkForm.querySelectorAll('input[name="selected_ids[]"]').forEach(el => el.remove());
        // Add selected order IDs to form
        checkboxes.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_ids[]';
            input.value = cb.value;
            bulkForm.appendChild(input);
        });
    } else {
        bulkActions.classList.remove('show');
    }
    
    // Update select-all checkbox state
    const allCheckboxes = document.querySelectorAll('.order-checkbox');
    document.getElementById('select-all').checked = count === allCheckboxes.length && allCheckboxes.length > 0;
}
</script>
</body>
</html>
