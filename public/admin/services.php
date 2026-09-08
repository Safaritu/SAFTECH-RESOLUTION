<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

// Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    header('Location: services.php');
    exit;
}

// Add or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $color_theme = trim($_POST['color_theme'] ?? 'sky');
    $sort_order = (int) ($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $id = $_POST['id'] ?? '';

    if ($title === '') {
        $error = 'Title is required.';
    } else {
        if ($id !== '') {
            $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, icon=?, color_theme=?, sort_order=?, is_active=? WHERE id=?");
            $stmt->execute([$title, $description, $icon, $color_theme, $sort_order, $is_active, (int) $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, color_theme, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $icon, $color_theme, $sort_order, $is_active]);
        }
        header('Location: services.php');
        exit;
    }
}

// Editing an existing one?
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editRow = $stmt->fetch();
}

$services = $pdo->query("SELECT * FROM services ORDER BY sort_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Services | Admin</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 14px; }
        th { background: #222; color: white; }
        form.card { background: white; padding: 15px; border: 1px solid #ddd; max-width: 500px; }
        form.card label { display: block; margin-top: 10px; font-size: 13px; font-weight: bold; }
        form.card input[type=text], form.card textarea, form.card input[type=number] { width: 100%; padding: 6px; box-sizing: border-box; }
        .error { color: red; }
        .actions a { margin-right: 8px; font-size: 13px; }
    </style>
</head>
<body>
    <p><a href="dashboard.php">&larr; Back to Dashboard</a></p>
    <h1>Manage Services</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <table>
        <tr><th>Order</th><th>Title</th><th>Icon</th><th>Color</th><th>Active</th><th>Actions</th></tr>
        <?php foreach ($services as $s): ?>
        <tr>
            <td><?= $s['sort_order'] ?></td>
            <td><?= htmlspecialchars($s['title']) ?></td>
            <td><?= htmlspecialchars($s['icon']) ?></td>
            <td><?= htmlspecialchars($s['color_theme']) ?></td>
            <td><?= $s['is_active'] ? 'Yes' : 'No' ?></td>
            <td class="actions">
                <a href="?edit=<?= $s['id'] ?>">Edit</a>
                <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Delete this service?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2><?= $editRow ? 'Edit Service' : 'Add New Service' ?></h2>
    <form class="card" method="POST">
        <input type="hidden" name="id" value="<?= $editRow ? $editRow['id'] : '' ?>">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" required>
        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($editRow['description'] ?? '') ?></textarea>
        <label>Icon (Font Awesome class, e.g. fa-laptop-code)</label>
        <input type="text" name="icon" value="<?= htmlspecialchars($editRow['icon'] ?? '') ?>">
        <label>Color theme (e.g. sky, purple, orange, emerald, pink, teal)</label>
        <input type="text" name="color_theme" value="<?= htmlspecialchars($editRow['color_theme'] ?? 'sky') ?>">
        <label>Sort order</label>
        <input type="number" name="sort_order" value="<?= htmlspecialchars($editRow['sort_order'] ?? 0) ?>">
        <label><input type="checkbox" name="is_active" <?= (!$editRow || $editRow['is_active']) ? 'checked' : '' ?> style="width:auto;"> Active (shown on site)</label>
        <br><br>
        <button type="submit"><?= $editRow ? 'Update' : 'Add' ?> Service</button>
        <?php if ($editRow): ?><a href="services.php">Cancel</a><?php endif; ?>
    </form>
</body>
</html>
