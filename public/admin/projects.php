<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    header('Location: projects.php');
    exit;
}

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

$editRow = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([(int) $_GET['edit']]);
    $editRow = $stmt->fetch();
}

$filter = $_GET['filter'] ?? 'all';
if ($filter !== 'all') {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE category = ? ORDER BY sort_order");
    $stmt->execute([$filter]);
    $projects = $stmt->fetchAll();
} else {
    $projects = $pdo->query("SELECT * FROM projects ORDER BY category, sort_order")->fetchAll();
}

$pageTitle = 'Manage Projects';
$totalCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();

$catLabels = ['live_demo' => 'Live Demo', 'enterprise' => 'Enterprise', 'portfolio' => 'Portfolio'];
$catColors = ['live_demo' => 'text-sky-300 bg-sky-500/15', 'enterprise' => 'text-purple-300 bg-purple-500/15', 'portfolio' => 'text-emerald-300 bg-emerald-500/15'];

require __DIR__ . '/../../includes/admin_header.php';
?>
<h1 class="text-2xl md:text-3xl font-bold mb-1">Manage Projects</h1>
<p class="text-slate-400 text-sm mb-6"><?= $totalCount ?> total projects</p>

<div class="flex gap-2 mb-6 text-sm flex-wrap">
    <a href="?filter=all" class="px-3 py-1.5 rounded-full <?= $filter === 'all' ? 'btn-primary font-semibold' : 'glass text-slate-400' ?>">All</a>
    <a href="?filter=live_demo" class="px-3 py-1.5 rounded-full <?= $filter === 'live_demo' ? 'btn-primary font-semibold' : 'glass text-slate-400' ?>">Live Demos</a>
    <a href="?filter=enterprise" class="px-3 py-1.5 rounded-full <?= $filter === 'enterprise' ? 'btn-primary font-semibold' : 'glass text-slate-400' ?>">Enterprise</a>
    <a href="?filter=portfolio" class="px-3 py-1.5 rounded-full <?= $filter === 'portfolio' ? 'btn-primary font-semibold' : 'glass text-slate-400' ?>">Portfolio</a>
</div>

<?php if ($error): ?><div class="mb-4 p-3 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-sm"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="glass rounded-2xl overflow-x-auto">
            <table class="admin-table">
                <tr><th>Order</th><th>Title</th><th>Category</th><th>Label</th><th></th></tr>
                <?php foreach ($projects as $p): ?>
                <tr>
                    <td><?= $p['sort_order'] ?></td>
                    <td class="font-semibold"><?= htmlspecialchars($p['title']) ?></td>
                    <td><span class="px-2 py-1 rounded-full text-xs <?= $catColors[$p['category']] ?? '' ?>"><?= $catLabels[$p['category']] ?? htmlspecialchars($p['category']) ?></span></td>
                    <td class="text-slate-400"><?= htmlspecialchars($p['category_label']) ?></td>
                    <td class="whitespace-nowrap">
                        <a href="?edit=<?= $p['id'] ?>&filter=<?= $filter ?>" class="text-sky-400 hover:underline mr-3">Edit</a>
                        <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this project?')" class="text-red-400 hover:underline">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$projects): ?>
                <tr><td colspan="5" class="text-center text-slate-500 py-8">No projects in this category yet.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div>
        <div class="glass rounded-2xl p-5">
            <h2 class="font-bold mb-2"><?= $editRow ? 'Edit Project' : 'Add New Project' ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editRow ? $editRow['id'] : '' ?>">

                <label class="form-label">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($editRow['title'] ?? '') ?>" required>

                <label class="form-label">Category</label>
                <select name="category">
                    <?php foreach ($catLabels as $val => $label): ?>
                        <option value="<?= $val ?>" <?= (($editRow['category'] ?? '') === $val) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="form-label">Category label <span class="normal-case font-normal text-slate-500">(Enterprise only, e.g. FINANCE)</span></label>
                <input type="text" name="category_label" value="<?= htmlspecialchars($editRow['category_label'] ?? '') ?>">

                <label class="form-label">Icon <span class="normal-case font-normal text-slate-500">(Enterprise, Font Awesome class)</span></label>
                <input type="text" name="icon" value="<?= htmlspecialchars($editRow['icon'] ?? '') ?>" placeholder="fa-coins">

                <label class="form-label">Tags <span class="normal-case font-normal text-slate-500">(comma-separated)</span></label>
                <input type="text" name="tags" value="<?= htmlspecialchars($editRow['tags'] ?? '') ?>" placeholder="Loans,Interest,Repayments">

                <label class="form-label">Color theme</label>
                <input type="text" name="color_theme" value="<?= htmlspecialchars($editRow['color_theme'] ?? 'sky') ?>" placeholder="sky, purple, orange...">

                <label class="form-label">Description</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($editRow['description'] ?? '') ?></textarea>

                <label class="form-label">Image URL</label>
                <input type="text" name="image_path" value="<?= htmlspecialchars($editRow['image_path'] ?? '') ?>">

                <label class="form-label">Project URL <span class="normal-case font-normal text-slate-500">(optional)</span></label>
                <input type="text" name="project_url" value="<?= htmlspecialchars($editRow['project_url'] ?? '') ?>">

                <label class="form-label">Sort order</label>
                <input type="number" name="sort_order" value="<?= htmlspecialchars($editRow['sort_order'] ?? 0) ?>">

                <div class="flex gap-4 mt-4 text-sm">
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_live" class="!w-auto" <?= (!empty($editRow['is_live'])) ? 'checked' : '' ?>> Is Live Demo</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" class="!w-auto" <?= (!empty($editRow['is_featured'])) ? 'checked' : '' ?>> Is Featured</label>
                </div>

                <button type="submit" class="w-full mt-5 py-2.5 btn-primary rounded-xl font-bold text-sm"><?= $editRow ? 'Update' : 'Add' ?> Project</button>
                <?php if ($editRow): ?><a href="projects.php" class="block text-center text-xs text-slate-400 mt-3 hover:text-white">Cancel edit</a><?php endif; ?>
            </form>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
