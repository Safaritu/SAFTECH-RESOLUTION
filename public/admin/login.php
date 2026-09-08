<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SAFTECH RESOLUTIONS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .gradient-text { background: linear-gradient(90deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .btn-primary { background: linear-gradient(90deg, #0ea5e9, #6366f1); transition: all 0.2s ease; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 20px -8px rgba(14, 165, 233, 0.5); }
        input[type=text], input[type=password] {
            width: 100%; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 10px 12px; color: #f8fafc; font-family: inherit; font-size: 14px;
        }
        input:focus { outline: none; border-color: #38bdf8; }
    </style>
</head>
<body>
    <div class="glass rounded-2xl p-8 w-full max-w-sm mx-4">
        <div class="text-center mb-6">
            <div class="text-2xl font-bold gradient-text mb-1">SAFTECH RESOLUTIONS</div>
            <p class="text-slate-400 text-sm">Admin Login</p>
        </div>
        <?php if ($error): ?>
            <div class="mb-4 p-3 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Username</label>
            <input type="text" name="username" required class="mb-4">
            <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Password</label>
            <input type="password" name="password" required class="mb-6">
            <button type="submit" class="w-full py-3 btn-primary rounded-xl font-bold">Log In</button>
        </form>
    </div>
</body>
</html>
