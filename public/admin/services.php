<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    header('Location: services.php');
    exit;
}

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

$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editRow = $stmt->fetch();
}

$pageTitle = 'Manage Services';
$services = $pdo->query("SELECT * FROM services ORDER BY sort_order")->fetchAll();

require __DIR__ . '/../../includes/admin_header.php';
?>
<h1 class="text-2xl md:text-3xl font-bold mb-1">Manage Services</h1>
<p class="text-slate-400 text-sm mb-6"><?= count($services) ?> services</p>
<?php if ($error): ?><div class="mb-4 p-3 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-sm"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="glass rounded-2xl overflow-x-auto">
            <table class="admin-table">
                <tr><th>Order</th><th>Title</th><th>Icon</th><th>Color</th><th>Active</th><th></th></tr>
                <?php foreach ($services as $s): ?>
                <tr>
                    <td><?= $s['sort_order'] ?></td>
                    <td class="font-semibold"><?= htmlspecialchars($s['title']) ?></td>
                    <td class="text-slate-400"><i class="fas <?= htmlspecialchars($s['icon']) ?>"></i> <?= htmlspecialchars($s['icon']) ?></td>
                    <td><span class="px-2 py-1 rounded-full text-xs bg-<?= htmlspecialchars($s['color_theme']) ?>-500/15 text-<?= htmlspecialchars($s['color_theme']) ?>-300"><?= htmlspecialchars($s['color_theme']) ?></span></td>
                    <td><?= $s['is_active'] ? '<span class="text-emerald-400">Yes</span>' : '<span class="text-slate-500">No</span>' ?></td>
                    <td class="whitespace-nowrap">
                        <a href="?edit=<?= $s['id'] ?>" class="text-sky-400 hover:underline mr-3">Edit</a>
                        <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Delete this service?')" class="text-red-400 hover:underline">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <div>
        <div class="glass rounded-2xl p-5">
            <h2 class="font-bold mb-2"><?= $editRow ? 'Edit Service' : 'Add New Service' ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editRow ? $editRow['id'] : '' ?>">
                <label class="form-label">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" required>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($editRow['description'] ?? '') ?></textarea>
                <label class="form-label">Icon (Font Awesome class)</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($editRow['icon'] ?? '') ?>" placeholder="fa-laptop-code">
                <label class="form-label">Color theme</label>
                <input type="text" name="color_theme" value="<?= htmlspecialchars($editRow['color_theme'] ?? 'sky') ?>" placeholder="sky, purple, orange...">
                <label class="form-label">Sort order</label>
                <input type="number" name="sort_order" value="<?= htmlspecialchars($editRow['sort_order'] ?? 0) ?>">
                <label class="flex items-center gap-2 mt-4 text-sm">
                    <input type="checkbox" name="is_active" class="!w-auto" <?= (!$editRow || $editRow['is_active']) ? 'checked' : '' ?>> Active (shown on site)
                </label>
                <button type="submit" class="w-full mt-5 py-2.5 btn-primary rounded-xl font-bold text-sm"><?= $editRow ? 'Update' : 'Add' ?> Service</button>
                <?php if ($editRow): ?><a href="services.php" class="block text-center text-xs text-slate-400 mt-3 hover:text-white">Cancel edit</a><?php endif; ?>
            </form>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
