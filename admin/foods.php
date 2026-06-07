<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

// Handle bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_selected') {
    $selected_ids = $_POST['selected_ids'] ?? [];
    
    if (!empty($selected_ids)) {
        try {
            foreach ($selected_ids as $food_id) {
                $stmt = $pdo->prepare("DELETE FROM food_items WHERE food_id = ?");
                $stmt->execute([$food_id]);
            }
            $delete_message = "✅ " . count($selected_ids) . " food item(s) deleted successfully!";
        } catch (PDOException $e) {
            $delete_error = "❌ Error deleting food items: " . $e->getMessage();
        }
    }
}

$stmt = $pdo->query("SELECT f.*, c.category_name FROM food_items f LEFT JOIN categories c ON f.category_id = c.category_id ORDER BY food_id DESC");
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Foods | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f7fafb; }
        .foods-card {
            background: #fff;
            box-shadow: 0 3px 18px #19a46318;
            border-radius: 16px;
            padding: 2.3rem 1.2rem;
            margin: 40px auto;
            max-width: 1100px;
        }
        .section-title { font-weight: 700; color: #19a463; margin-bottom: .9rem; font-size:1.22rem;}
        .add-link {
            display: inline-block;
            margin-bottom: 18px;
            margin-right: 10px;
            font-weight: 500;
            color: #fff;
            background: #11b981;
            padding: .6em 1.3em;
            border-radius: 11px;
            text-decoration: none;
            transition: .17s;
        }
        .add-link:hover {
            background: #14834e;
            color: #fff;
        }
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
        .food-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e0e5e2;
            background: #f5f5f5;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .food-img:hover {
            transform: scale(1.05);
        }
        .checkbox-cell {
            text-align: center;
            width: 50px;
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
        /* Modal for image preview */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }
        .image-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            position: relative;
            animation: slideUp 0.3s;
        }
        .image-modal-content img {
            width: 100%;
            border-radius: 8px;
            max-height: 400px;
            object-fit: cover;
        }
        .close-modal {
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            background: none;
            border: none;
        }
        .close-modal:hover {
            color: #000;
        }
        .image-modal-title {
            color: #19a463;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="foods-card">
    <a href="dashboard.php" class="dashboard-link"><i class="bi bi-house"></i> Back to Dashboard</a>
    <div class="section-title">Manage Foods</div>

    <?php if (isset($delete_message)): ?>
        <div class="message-alert message-success" id="notification"><?= htmlspecialchars($delete_message) ?></div>
    <?php endif; ?>
    
    <?php if (isset($delete_error)): ?>
        <div class="message-alert message-error" id="notification"><?= htmlspecialchars($delete_error) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="food_add.php" class="add-link"><i class="bi bi-plus-lg"></i> Add New Food</a>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulk-actions">
        <form method="POST" id="bulk-form" onsubmit="return confirm('Delete selected food items?');">
            <input type="hidden" name="action" value="delete_selected">
            <div>
                <span id="selected-count">0 items selected</span>
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
    <table class="table table-bordered align-middle" id="foods-table">
        <thead class="table-success">
            <tr>
                <th class="checkbox-cell">
                    <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                </th>
                <th>Image</th>
                <th>Name</th>
                <th>Price (TSh)</th>
                <th>Category</th>
                <th>Active</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="foods-body">
        <?php if (count($foods) > 0): ?>
            <?php foreach($foods as $f): ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="food-checkbox" value="<?=$f['food_id']?>" onchange="updateBulkActions()">
                    </td>
                    <td>
                        <img 
                            src="<?= ($f['image_url'] && strlen($f['image_url']) > 4) ? htmlspecialchars($f['image_url']) : 'assets/no-image.png' ?>" 
                            class="food-img" 
                            alt="<?=htmlspecialchars($f['name'])?>"
                            onclick="openImageModal('<?= htmlspecialchars($f['image_url'] ?? 'assets/no-image.png') ?>', '<?=htmlspecialchars($f['name'])?>')"
                            title="Click to view larger image"
                        >
                    </td>
                    <td><?=htmlspecialchars($f['name'])?></td>
                    <td><?=number_format($f['price'],0)?></td>
                    <td><?=htmlspecialchars($f['category_name'] ?? 'N/A')?></td>
                    <td><?= (isset($f['is_active']) && $f['is_active']) ? "<span class='badge bg-success'>Yes</span>" : "<span class='badge bg-secondary'>No</span>" ?></td>
                    <td><?=date('d M Y', strtotime($f['created_at'] ?? date('Y-m-d'))) ?></td>
                    <td>
                        <a href="food_edit.php?id=<?=$f['food_id']?>" class="btn btn-sm btn-warning" title="Edit Food">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="food_delete.php?id=<?=$f['food_id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this food item?')" title="Delete Food">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                    No food items found. <a href="food_add.php">Add one now</a>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="image-modal" id="imageModal">
    <div class="image-modal-content">
        <button class="close-modal" onclick="closeImageModal()">&times;</button>
        <div class="image-modal-title" id="imageName"></div>
        <img id="modalImage" src="" alt="Food Image">
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

// Image Modal Functions
function openImageModal(imageSrc, foodName) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const imageName = document.getElementById('imageName');
    
    modalImage.src = imageSrc;
    imageName.textContent = foodName;
    modal.classList.add('show');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('show');
}

window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        modal.classList.remove('show');
    }
}

// Select all checkbox
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.food-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActions();
}

// Uncheck all
function uncheckAll() {
    document.getElementById('select-all').checked = false;
    document.querySelectorAll('.food-checkbox').forEach(cb => cb.checked = false);
    updateBulkActions();
}

// Update bulk actions display
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.food-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-form');
    
    const count = checkboxes.length;
    selectedCount.textContent = count + (count === 1 ? ' item selected' : ' items selected');
    
    if (count > 0) {
        bulkActions.classList.add('show');
        // Clear previous checkboxes
        bulkForm.querySelectorAll('input[name="selected_ids[]"]').forEach(el => el.remove());
        // Add selected food IDs to form
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
    const allCheckboxes = document.querySelectorAll('.food-checkbox');
    document.getElementById('select-all').checked = count === allCheckboxes.length && allCheckboxes.length > 0;
}
</script>
</body>
</html>
