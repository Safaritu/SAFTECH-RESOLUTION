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
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    header('Location: projects.php');
    exit;
}

// Add or Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'portfolio');
    $category_label = trim($_POST['category_label'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $color_theme = trim($_POST['color_theme'] ?? 'sky');
    $is_live = isset($_POST['is_live']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $sort_order = (int) ($_POST['sort_order'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image_path = trim($_POST['image_path'] ?? '');
    $project_url = trim($_POST['project_url'] ?? '');
    $id = $_POST['id'] ?? '';

    if ($title === '') {
        $error = 'Title is required.';
    } else {
        if ($id !== '') {
            $stmt = $pdo->prepare("UPDATE projects SET title=?, category=?, category_label=?, icon=?, tags=?, color_theme=?, is_live=?, is_featured=?, sort_order=?, description=?, image_path=?, project_url=? WHERE id=?");
            $stmt->execute([$title, $category, $category_label, $icon, $tags, $color_theme, $is_live, $is_featured, $sort_order, $description, $image_path, $project_url, (int) $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO projects (title, category, category_label, icon, tags, color_theme, is_live, is_featured, sort_order, description, image_path, project_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category, $category_label, $icon, $tags, $color_theme, $is_live, $is_featured, $sort_order, $description, $image_path, $project_url]);
        }
        header('Location: projects.php' . ($category !== 'portfolio' ? '?filter=' . urlencode($category) : ''));
        exit;
    }
}

// Editing an existing one?
$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editRow = $stmt->fetch();
}

// Filter by category
$filter = $_GET['filter'] ?? 'all';
if ($filter !== 'all') {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE category = ? ORDER BY sort_order");
    $stmt->execute([$filter]);
    $projects = $stmt->fetchAll();
} else {
    $projects = $pdo->query("SELECT * FROM projects ORDER BY category, sort_order")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Projects | Admin</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 13px; }
        th { background: #222; color: white; }
        form.card { background: white; padding: 15px; border: 1px solid #ddd; max-width: 600px; }
        form.card label { display: block; margin-top: 10px; font-size: 13px; font-weight: bold; }
        form.card input[type=text], form.card textarea, form.card input[type=number], form.card select { width: 100%; padding: 6px; box-sizing: border-box; }
        .error { color: red; }
        .actions a { margin-right: 8px; font-size: 13px; }
        .filters a { margin-right: 12px; }
        .filters a.active { font-weight: bold; text-decoration: underline; }
        .checkbox-row label { display: inline; font-weight: normal; margin-right: 15px; }
        .checkbox-row input { width: auto; }
    </style>
</head>
<body>
    <p><a href="dashboard.php">&larr; Back to Dashboard</a></p>
    <h1>Manage Projects</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <p class="filters">
        Filter: 
        <a href="?filter=all" class="<?= $filter === 'all' ? 'active' : '' ?>">All (<?= count($pdo->query("SELECT id FROM projects")->fetchAll()) ?>)</a>
        <a href="?filter=live_demo" class="<?= $filter === 'live_demo' ? 'active' : '' ?>">Live Demos</a>
        <a href="?filter=enterprise" class="<?= $filter === 'enterprise' ? 'active' : '' ?>">Enterprise</a>
        <a href="?filter=portfolio" class="<?= $filter === 'portfolio' ? 'active' : '' ?>">Portfolio</a>
    </p>

    <table>
        <tr><th>Order</th><th>Title</th><th>Category</th><th>Label</th><th>Actions</th></tr>
        <?php foreach ($projects as $p): ?>
        <tr>
            <td><?= $p['sort_order'] ?></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><?= htmlspecialchars($p['category']) ?></td>
            <td><?= htmlspecialchars($p['category_label']) ?></td>
            <td class="actions">
                <a href="?edit=<?= $p['id'] ?>">Edit</a>
                <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this project?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2><?= $editRow ? 'Edit Project' : 'Add New Project' ?></h2>
    <form class="card" method="POST">
        <input type="hidden" name="id" value="<?= $editRow ? $editRow['id'] : '' ?>">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" required>

        <label>Category</label>
        <select name="category">
            <?php $cats = ['live_demo' => 'Live Demo', 'enterprise' => 'Enterprise Solution', 'portfolio' => 'Portfolio']; ?>
            <?php foreach ($cats as $val => $label): ?>
                <option value="<?= $val ?>" <?= (($editRow['category'] ?? '') === $val) ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>

        <label>Category label (Enterprise only, e.g. FINANCE, ERP, HR)</label>
        <input type="text" name="category_label" value="<?= htmlspecialchars($editRow['category_label'] ?? '') ?>">

        <label>Icon (Enterprise only, Font Awesome class, e.g. fa-coins)</label>
        <input type="text" name="icon" value="<?= htmlspecialchars($editRow['icon'] ?? '') ?>">

        <label>Tags (Enterprise only, comma-separated, e.g. Loans,Interest,Repayments)</label>
        <input type="text" name="tags" value="<?= htmlspecialchars($editRow['tags'] ?? '') ?>">

        <label>Color theme (Live Demo / Portfolio, e.g. sky, purple, orange, emerald, pink, teal)</label>
        <input type="text" name="color_theme" value="<?= htmlspecialchars($editRow['color_theme'] ?? 'sky') ?>">

        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($editRow['description'] ?? '') ?></textarea>

        <label>Image URL</label>
        <input type="text" name="image_path" value="<?= htmlspecialchars($editRow['image_path'] ?? '') ?>">

        <label>Project URL (optional live link)</label>
        <input type="text" name="project_url" value="<?= htmlspecialchars($editRow['project_url'] ?? '') ?>">

        <label>Sort order</label>
        <input type="number" name="sort_order" value="<?= htmlspecialchars($editRow['sort_order'] ?? 0) ?>">

        <div class="checkbox-row" style="margin-top:10px;">
            <label><input type="checkbox" name="is_live" <?= (!empty($editRow['is_live'])) ? 'checked' : '' ?>> Is Live Demo</label>
            <label><input type="checkbox" name="is_featured" <?= (!empty($editRow['is_featured'])) ? 'checked' : '' ?>> Is Featured (Portfolio)</label>
        </div>

        <br>
        <button type="submit"><?= $editRow ? 'Update' : 'Add' ?> Project</button>
        <?php if ($editRow): ?><a href="projects.php">Cancel</a><?php endif; ?>
    </form>
</body>
</html>
