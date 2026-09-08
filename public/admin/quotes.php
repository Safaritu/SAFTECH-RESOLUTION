<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle status update
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

$stmt = $pdo->query("SELECT * FROM quote_requests ORDER BY created_at DESC");
$quotes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quote Requests | Admin</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #222; color: white; }
        .status-new { color: #b91c1c; font-weight: bold; }
        .status-contacted { color: #b45309; font-weight: bold; }
        .status-closed { color: #15803d; font-weight: bold; }
        select { padding: 4px; }
    </style>
</head>
<body>
    <p><a href="dashboard.php">&larr; Back to Dashboard</a></p>
    <h1>Quote Requests (<?= count($quotes) ?>)</h1>
    <table>
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
            <td><?= htmlspecialchars($q['created_at']) ?></td>
            <td><?= htmlspecialchars($q['name']) ?></td>
            <td><?= htmlspecialchars($q['email']) ?></td>
            <td><?= htmlspecialchars($q['project_interest']) ?></td>
            <td><?= nl2br(htmlspecialchars($q['message'])) ?></td>
            <td>
                <span class="status-<?= htmlspecialchars($q['status']) ?>"><?= htmlspecialchars($q['status']) ?></span>
                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="id" value="<?= $q['id'] ?>">
                    <select name="status">
                        <option value="new" <?= $q['status'] === 'new' ? 'selected' : '' ?>>New</option>
                        <option value="contacted" <?= $q['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                        <option value="closed" <?= $q['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                    <button type="submit" name="update_status" value="1">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
