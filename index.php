<?php
require_once 'bootstrap.php';
$pageTitle = 'Home';
$eventCount = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$studentCount = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
require 'partials/header.php';
?>
<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">One place for your campus life</p>
        <h1>Your student life,<br>made a little simpler.</h1>
        <p>StudentWDF brings course information, campus events and student records together in one easy portal.</p>
        <div class="hero-actions">
            <a class="button button-light" href="courses.php">Explore courses</a>
            <a class="button button-outline" href="events.php" style="border-color:rgba(255,255,255,.6);color:white">View events</a>
            <button type="button" class="button button-light" data-open-modal>Quick help</button>
        </div>
    </div>
</section>
<div class="notice-bar" style="margin-top:18px">
    <span>
<strong>Student note:</strong> Register for an account to access your dashboard and campus event sign-ups.</span>
    <button type="button" data-dismiss aria-label="Dismiss notice">✕</button>
</div>
<div class="section-head">
<div>
<p class="eyebrow">Quick overview</p>
<h2>Everything in one place</h2>
</div>
<button class="button button-outline button-small" type="button" data-theme-toggle>Toggle theme</button>
</div>
<section class="grid">
    <article class="card">
<div class="card-icon">📚</div>
<h3>Course information</h3>
<p>Find short descriptions of the subjects and skills covered in the semester.</p>
<a href="courses.php">Explore courses →</a>
</article>
    <article class="card">
<div class="card-icon">🎟️</div>
<h3>Campus events</h3>
<p>See upcoming activities and register for events after signing in.</p>
<a href="events.php">Browse events →</a>
</article>
    <article class="card">
<div class="card-icon">📊</div>
<h3>Data Lab</h3>
<p>Practise JSON loading, searching, sorting and pagination with sample student data.</p>
<a href="data-lab.php">Open Data Lab →</a>
</article>
</section>
<div class="section-head">
<div>
<p class="eyebrow">At a glance</p>
<h2>StudentWDF today</h2>
</div>
</div>
<section class="grid">
    <div class="stat-card">
<strong><?= $studentCount ?></strong>
<span>Student records</span>
</div>
    <div class="stat-card">
<strong><?= $eventCount ?></strong>
<span>Campus events</span>
</div>
    <div class="stat-card">
<strong>14</strong>
<span>Practical topics supported</span>
</div>
</section>
<section class="panel" style="margin-top:22px">
    <p class="eyebrow">Student tip</p>
<h2 data-tip-text>Keep your profile details up to date.</h2>
    <p class="muted">A small reminder that changes each time you use the button.</p>
    <button type="button" class="button button-small" data-next-tip>Next tip</button>
</section>
<div hidden data-modal class="panel" style="margin-top:20px">
<h2>Quick help</h2>
<p>Use the navigation menu to explore the portal, or register to try the logged-in features.</p>
<button type="button" class="button" data-close-modal>Close</button>
</div>
<?php require 'partials/footer.php'; ?>
