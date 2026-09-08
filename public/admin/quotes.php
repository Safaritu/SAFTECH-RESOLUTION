<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];
    $allowed = ['new', 'contacted', 'closed'];
    if (in_array($status, $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE quote_requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }
    header('Location: quotes.php');
    exit;
}

$pageTitle = 'Quote Requests';
$stmt = $pdo->query("SELECT * FROM quote_requests ORDER BY created_at DESC");
$quotes = $stmt->fetchAll();

$statusStyles = [
    'new' => 'bg-red-500/15 text-red-300 border-red-500/30',
    'contacted' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
    'closed' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
];

require __DIR__ . '/../../includes/admin_header.php';
?>
<h1 class="text-2xl md:text-3xl font-bold mb-1">Quote Requests</h1>
<p class="text-slate-400 text-sm mb-6"><?= count($quotes) ?> total submissions</p>

<div class="glass rounded-2xl overflow-x-auto">
    <table class="admin-table">
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Email</th>
            <th>Interested In</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
        <?php foreach ($quotes as $q): ?>
        <tr>
            <td class="whitespace-nowrap text-slate-400"><?= htmlspecialchars($q['created_at']) ?></td>
            <td class="font-semibold"><?= htmlspecialchars($q['name']) ?></td>
            <td><?= htmlspecialchars($q['email']) ?></td>
            <td><?= htmlspecialchars($q['project_interest'] ?: '—') ?></td>
            <td class="max-w-xs"><?= nl2br(htmlspecialchars($q['message'])) ?></td>
            <td>
                <span class="inline-block px-2 py-1 rounded-full text-xs border mb-2 <?= $statusStyles[$q['status']] ?? '' ?>"><?= htmlspecialchars($q['status']) ?></span>
                <form method="POST" class="flex gap-2 items-center">
                    <input type="hidden" name="id" value="<?= $q['id'] ?>">
                    <select name="status" class="!w-auto !py-1 !text-xs">
                        <option value="new" <?= $q['status'] === 'new' ? 'selected' : '' ?>>New</option>
                        <option value="contacted" <?= $q['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                        <option value="closed" <?= $q['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                    <button type="submit" name="update_status" value="1" class="text-xs px-3 py-1 btn-primary rounded-lg font-semibold">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$quotes): ?>
        <tr><td colspan="6" class="text-center text-slate-500 py-8">No quote requests yet.</td></tr>
        <?php endif; ?>
    </table>
</div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
