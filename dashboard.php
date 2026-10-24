<?php
require_once 'bootstrap.php';
require_login();
$pageTitle = 'Dashboard';
$stmt = $pdo->prepare('SELECT events.title, events.event_date, events.location FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ? ORDER BY registrations.created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$myEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
require 'partials/header.php';
?>
<section class="hero">
<div class="hero-content">
<p class="eyebrow">Student dashboard</p>
<h1>Hello, <?= e($_SESSION['name']) ?>!</h1>
<p>Welcome to your student account. You can view events and keep track of your sign-ups here.</p>
<div class="hero-actions">
<a class="button button-light" href="events.php">Explore events</a>
<a class="button button-outline" href="contact.php" style="border-color:rgba(255,255,255,.6);color:white">Contact us</a>
</div>
</div>
</section>
<div class="section-head">
<div>
<h2>My event registrations</h2>
<p>Events you have signed up for appear here.</p>
</div>
</div>
<?php if ($myEvents): ?><section class="grid"><?php foreach ($myEvents as $event): ?><article class="card">
<span class="event-date"><?= e($event['event_date']) ?></span>
<h3 style="margin-top:10px"><?= e($event['title']) ?></h3>
<p class="muted">📍 <?= e($event['location']) ?></p>
</article><?php endforeach; ?></section><?php else: ?><div class="panel empty-state">You have not registered for any events yet. <a href="events.php">Browse events</a>.</div><?php endif; ?>
<?php require 'partials/footer.php'; ?>
