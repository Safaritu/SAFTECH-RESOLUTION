<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SAFTECH RESOLUTIONS</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username']) ?></h1>
    <p>This is your admin dashboard. We'll add bookings, services, and project management here next.</p>
    <a href="quotes.php">View Quote Requests</a> &nbsp;&#124;&nbsp; <a href="logout.php">Log Out</a>
</body>
</html>
