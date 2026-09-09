<?php
require_once __DIR__ . '/../includes/db.php';

$success = false;
$error = '';

$services = $pdo->query("SELECT * FROM services WHERE is_active=1 ORDER BY sort_order")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['client_name'] ?? '');
    $email = trim($_POST['client_email'] ?? '');
    $phone = trim($_POST['client_phone'] ?? '');
    $service_id = $_POST['service_id'] !== '' ? (int) $_POST['service_id'] : null;
    $preferred_date = $_POST['preferred_date'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '') {
        $error = 'Please provide your name and email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO bookings (client_name, client_email, client_phone, service_id, preferred_date, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $service_id, $preferred_date ?: null, $message]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service | SAFTECH RESOLUTIONS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #f8fafc; min-height: 100vh; }
        .gradient-text { background: linear-gradient(90deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .btn-primary { background: linear-gradient(90deg, #0ea5e9, #6366f1); transition: all 0.2s ease; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 20px -8px rgba(14, 165, 233, 0.5); }
        input[type=text], input[type=email], input[type=tel], input[type=date], textarea, select {
            width: 100%; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 10px 12px; color: #f8fafc; font-family: inherit; font-size: 14px;
        }
        input:focus, textarea:focus, select:focus { outline: none; border-color: #38bdf8; }
        label.form-label { display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 4px; margin-top: 14px; text-transform: uppercase; letter-spacing: 0.03em; }
    </style>
</head>
<body>
    <div class="max-w-xl mx-auto px-4 py-14">
        <a href="index.php" class="text-sky-400 text-sm">&larr; Back to home</a>
        <div class="text-center mt-6 mb-8">
            <div class="text-2xl font-bold gradient-text mb-1">Book a Service</div>
            <p class="text-slate-400 text-sm">Tell us what you need and when — we'll confirm within 24 hours.</p>
        </div>

        <div class="glass rounded-2xl p-6">
            <?php if ($success): ?>
                <div class="p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm text-center">
                    <i class="fas fa-check-circle mr-1"></i> Booking received! We'll reach out to confirm the details shortly.
                </div>
                <a href="index.php" class="block text-center mt-5 text-sky-400 text-sm">&larr; Return to homepage</a>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="mb-4 p-3 rounded-xl bg-red-500/15 border border-red-500/30 text-red-300 text-sm"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="client_name" value="<?= htmlspecialchars($_POST['client_name'] ?? '') ?>" required>

                    <label class="form-label">Email</label>
                    <input type="email" name="client_email" value="<?= htmlspecialchars($_POST['client_email'] ?? '') ?>" required>

                    <label class="form-label">Phone (optional)</label>
                    <input type="tel" name="client_phone" value="<?= htmlspecialchars($_POST['client_phone'] ?? '') ?>">

                    <label class="form-label">Service</label>
                    <select name="service_id">
                        <option value="">General Inquiry</option>
                        <?php foreach ($services as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['title']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label class="form-label">Preferred Date (optional)</label>
                    <input type="date" name="preferred_date">

                    <label class="form-label">Project Details</label>
                    <textarea name="message" rows="4" placeholder="Tell us about your project..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

                    <button type="submit" class="w-full mt-6 py-3 btn-primary rounded-xl font-bold">Submit Booking</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
