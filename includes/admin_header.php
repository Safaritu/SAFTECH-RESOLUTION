<?php
$currentPage = basename($_SERVER['PHP_SELF']);
function navClass($page, $current) {
    return $page === $current
        ? 'text-sky-400 border-b-2 border-sky-400'
        : 'text-slate-400 hover:text-white border-b-2 border-transparent';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Admin' : 'Admin' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #f8fafc; }
        .gradient-text { background: linear-gradient(90deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .btn-primary { background: linear-gradient(90deg, #0ea5e9, #6366f1); transition: all 0.2s ease; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 20px -8px rgba(14, 165, 233, 0.5); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.25); }
        table.admin-table { width: 100%; border-collapse: collapse; }
        table.admin-table th { text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; padding: 10px 12px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        table.admin-table td { padding: 10px 12px; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 14px; }
        table.admin-table tr:hover td { background: rgba(255,255,255,0.02); }
        input[type=text], input[type=email], input[type=password], input[type=number], textarea, select {
            width: 100%; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 10px 12px; color: #f8fafc; font-family: inherit; font-size: 14px;
        }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #38bdf8; }
        label.form-label { display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 4px; margin-top: 14px; text-transform: uppercase; letter-spacing: 0.03em; }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['admin_id'])): ?>
    <header class="glass sticky top-0 z-50 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="text-lg font-bold gradient-text">SAFTECH ADMIN</div>
            <nav class="flex gap-5 text-sm font-medium">
                <a href="dashboard.php" class="pb-1 <?= navClass('dashboard.php', $currentPage) ?>">Dashboard</a>
                <a href="quotes.php" class="pb-1 <?= navClass('quotes.php', $currentPage) ?>">Quote Requests</a>
                <a href="services.php" class="pb-1 <?= navClass('services.php', $currentPage) ?>">Services</a>
                <a href="projects.php" class="pb-1 <?= navClass('projects.php', $currentPage) ?>">Projects</a>
            </nav>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-slate-400">Hi, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                <a href="logout.php" class="text-red-400 hover:text-red-300">Log Out</a>
            </div>
        </div>
    </header>
    <?php endif; ?>
    <main class="max-w-7xl mx-auto px-4 py-8">
