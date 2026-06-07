<?php
require_once 'admin_auth.php';
require_once '../includes/db.php';

// Handle deletion
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM users WHERE user_id=?")->execute([$id]);
    header("Location: users.php");
    exit;
}

// Handle unblock
if (isset($_GET['unblock'])) {
    $id = intval($_GET['unblock']);
    $pdo->prepare("UPDATE users SET status='active' WHERE user_id=?")->execute([$id]);
    header("Location: users.php?unblocked=$id");
    exit;
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users ORDER BY user_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-5">
    <h2>Users
        <a href="add_user.php" class="btn btn-success btn-sm float-end">Add User</a>
    </h2>
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($users as $u): ?>
            <tr>
                <td><?= $u['user_id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone']) ?></td>
                <td><?= isset($u['role']) ? htmlspecialchars($u['role']) : 'user' ?></td>
                <td>
                    <?php
                        $status = isset($u['status']) ? $u['status'] : 'active';
                        if ($status == 'blocked') {
                            echo "<span class='badge bg-danger'>Blocked</span>";
                        } else {
                            echo "<span class='badge bg-success'>Active</span>";
                        }
                    ?>
                </td>
                <td><?= isset($u['created_at']) ? $u['created_at'] : '' ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="users.php?del=<?= $u['user_id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                    <?php if ($status == 'blocked'): ?>
                        <a href="users.php?unblock=<?= $u['user_id'] ?>" class="btn btn-sm btn-warning">Unblock</a>
                    <?php else: ?>
                        <a href="block_user.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Block this user?')">Block</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
</body>
</html>