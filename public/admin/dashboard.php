<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Dashboard';
$newQuotes = $pdo->query("SELECT COUNT(*) FROM quote_requests WHERE status='new'")->fetchColumn();
$totalQuotes = $pdo->query("SELECT COUNT(*) FROM quote_requests")->fetchColumn();
$totalServices = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$totalProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();

require __DIR__ . '/../../includes/admin_header.php';
?>
<h1 class="text-2xl md:text-3xl font-bold mb-1">Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h1>
<p class="text-slate-400 text-sm mb-8">Here's what's happening with your portfolio.</p>

<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
    <div class="glass rounded-2xl p-5">
        <div class="text-3xl font-bold text-sky-400"><?= $newQuotes ?></div>
        <div class="text-xs text-slate-400 mt-1">New Quote Requests</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="text-3xl font-bold text-indigo-400"><?= $totalQuotes ?></div>
        <div class="text-xs text-slate-400 mt-1">Total Quote Requests</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="text-3xl font-bold text-emerald-400"><?= $totalServices ?></div>
        <div class="text-xs text-slate-400 mt-1">Services Listed</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="text-3xl font-bold text-purple-400"><?= $totalProjects ?></div>
        <div class="text-xs text-slate-400 mt-1">Projects Listed</div>
    </div>
</div>

<div class="grid sm:grid-cols-3 gap-4">
    <a href="quotes.php" class="glass rounded-2xl p-6 card-hover block hover:border-sky-400 transition">
        <i class="fas fa-envelope-open-text text-sky-400 text-2xl mb-3"></i>
        <h3 class="font-bold mb-1">Quote Requests</h3>
        <p class="text-xs text-slate-400">Review and respond to client inquiries.</p>
    </a>
    <a href="services.php" class="glass rounded-2xl p-6 card-hover block hover:border-sky-400 transition">
        <i class="fas fa-briefcase text-emerald-400 text-2xl mb-3"></i>
        <h3 class="font-bold mb-1">Manage Services</h3>
        <p class="text-xs text-slate-400">Edit what services you offer clients.</p>
    </a>
    <a href="projects.php" class="glass rounded-2xl p-6 card-hover block hover:border-sky-400 transition">
        <i class="fas fa-diagram-project text-purple-400 text-2xl mb-3"></i>
        <h3 class="font-bold mb-1">Manage Projects</h3>
        <p class="text-xs text-slate-400">Edit live demos, enterprise solutions, and portfolio pieces.</p>
    </a>
</div>
<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
