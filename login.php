<?php
require_once 'bootstrap.php';
$pageTitle = 'Login';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        log_action($pdo, 'Logged in');
        header('Location: ' . ($user['role'] === 'admin' ? 'admin_dashboard.php' : 'dashboard.php'));
        exit;
    }
    $error = 'Email or password was not correct.';
}
require 'partials/header.php';
?>
<section class="panel narrow" style="margin:0 auto">
    <p class="eyebrow">Welcome back</p>
<h1>Log in</h1>
<p class="muted">Use the account you registered with.</p>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'admin'): ?><div class="notice error">Please log in using an administrator account to open that page.</div><?php endif; ?>
    <?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="login.php">
        <div class="form-group">
<label for="email">Email address</label>
<input type="email" id="email" name="email" required>
</div>
        <div class="form-group">
<label for="password">Password</label>
<input type="password" id="password" name="password" required>
</div>
        <button class="button" type="submit">Log in</button>
    </form>
    <p class="muted small" style="margin-top:18px">For local admin testing: admin@studentwdf.local / Admin@12345</p>
</section>
<?php require 'partials/footer.php'; ?>
