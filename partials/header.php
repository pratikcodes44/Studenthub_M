<?php
$pageTitle = $pageTitle ?? 'StudentWDF';
$activePage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | StudentWDF</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="index.php">
<span class="brand-mark">S</span>
<span>Student<span class="brand-light">WDF</span>
</span>
</a>
        <button class="menu-button" type="button" data-menu-toggle aria-label="Toggle navigation">☰</button>
        <nav class="nav-links" data-nav-links>
            <a href="index.php" class="<?= $activePage === 'index.php' ? 'active' : '' ?>">Home</a>
            <a href="about.php" class="<?= $activePage === 'about.php' ? 'active' : '' ?>">About</a>
            <a href="courses.php" class="<?= $activePage === 'courses.php' ? 'active' : '' ?>">Courses</a>
            <a href="events.php" class="<?= $activePage === 'events.php' ? 'active' : '' ?>">Events</a>
            <a href="data-lab.php" class="<?= $activePage === 'data-lab.php' ? 'active' : '' ?>">Data Lab</a>
            <a href="faq.php" class="<?= $activePage === 'faq.php' ? 'active' : '' ?>">FAQ</a>
            <a href="contact.php" class="<?= $activePage === 'contact.php' ? 'active' : '' ?>">Contact</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?><a href="admin_dashboard.php">Admin</a><?php endif; ?>
                <a class="nav-cta" href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a class="nav-cta" href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container main-content">
