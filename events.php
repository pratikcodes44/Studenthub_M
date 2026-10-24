<?php
require_once 'bootstrap.php';
$pageTitle = 'Events';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    require_login();
    $eventId = (int)$_POST['event_id'];
    try {
        $stmt = $pdo->prepare('INSERT INTO registrations (user_id, event_id) VALUES (?, ?)');
        $stmt->execute([$_SESSION['user_id'], $eventId]);
        log_action($pdo, 'Registered for event #' . $eventId);
        $message = 'You are registered for this event.';
    } catch (PDOException $e) {
        $message = 'You may already be registered for this event.';
    }
}
$events = $pdo->query('SELECT * FROM events ORDER BY event_date')->fetchAll(PDO::FETCH_ASSOC);
require 'partials/header.php';
?>
<p class="eyebrow">Campus calendar</p>
<h1>Upcoming events</h1>
<p class="muted">Workshops, student activities and learning opportunities.</p>
<?php if ($message): ?><div class="notice success"><?= e($message) ?></div><?php endif; ?>
<section class="grid">
<?php foreach ($events as $event): ?>
    <article class="card">
        <?php if (!empty($event['poster']) && file_exists(__DIR__ . '/uploads/' . $event['poster'])): ?><img class="poster-preview" src="uploads/<?= e($event['poster']) ?>" alt="Event poster"><?php else: ?><div class="card-icon">📅</div><?php endif; ?>
        <span class="tag"><?= e($event['category']) ?></span>
<h3 style="margin-top:12px"><?= e($event['title']) ?></h3>
        <p class="event-date"><?= e($event['event_date']) ?></p>
<p><?= e($event['description']) ?></p>
<p class="muted">📍 <?= e($event['location']) ?></p>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') !== 'admin'): ?><form method="post">
<input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
<button class="button button-small" type="submit">Register for event</button>
</form><?php elseif (!isset($_SESSION['user_id'])): ?><a class="button button-small" href="login.php">Log in to register</a><?php endif; ?>
    </article>
<?php endforeach; ?>
</section>
<?php require 'partials/footer.php'; ?>
