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
    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (in_array($status, $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }
    header('Location: bookings.php');
    exit;
}

$pageTitle = 'Bookings';
$bookings = $pdo->query("
    SELECT b.*, s.title AS service_title
    FROM bookings b
    LEFT JOIN services s ON b.service_id = s.id
    ORDER BY b.created_at DESC
")->fetchAll();

$statusStyles = [
    'pending' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
    'confirmed' => 'bg-sky-500/15 text-sky-300 border-sky-500/30',
    'completed' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
    'cancelled' => 'bg-red-500/15 text-red-300 border-red-500/30',
];

require __DIR__ . '/../../includes/admin_header.php';
?>
<h1 class="text-2xl md:text-3xl font-bold mb-1">Bookings</h1>
<p class="text-slate-400 text-sm mb-6"><?= count($bookings) ?> total bookings</p>

<div class="glass rounded-2xl overflow-x-auto">
    <table class="admin-table">
        <tr>
            <th>Date</th>
            <th>Client</th>
            <th>Contact</th>
            <th>Service</th>
            <th>Preferred Date</th>
            <th>Notes</th>
            <th>Status</th>
        </tr>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td class="whitespace-nowrap text-slate-400"><?= htmlspecialchars($b['created_at']) ?></td>
            <td class="font-semibold"><?= htmlspecialchars($b['client_name']) ?></td>
            <td>
                <div><?= htmlspecialchars($b['client_email']) ?></div>
                <?php if ($b['client_phone']): ?><div class="text-slate-400 text-xs"><?= htmlspecialchars($b['client_phone']) ?></div><?php endif; ?>
            </td>
            <td><?= htmlspecialchars($b['service_title'] ?? 'General Inquiry') ?></td>
            <td class="whitespace-nowrap"><?= $b['preferred_date'] ? htmlspecialchars($b['preferred_date']) : '—' ?></td>
            <td class="max-w-xs"><?= nl2br(htmlspecialchars($b['message'] ?: '—')) ?></td>
            <td>
                <span class="inline-block px-2 py-1 rounded-full text-xs border mb-2 <?= $statusStyles[$b['status']] ?? '' ?>"><?= htmlspecialchars($b['status']) ?></span>
                <form method="POST" class="flex gap-2 items-center">
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    <select name="status" class="!w-auto !py-1 !text-xs">
                        <option value="pending" <?= $b['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= $b['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="completed" <?= $b['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $b['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" value="1" class="text-xs px-3 py-1 btn-primary rounded-lg font-semibold">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$bookings): ?>
        <tr><td colspan="7" class="text-center text-slate-500 py-8">No bookings yet.</td></tr>
        <?php endif; ?>
    </table>
</div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
