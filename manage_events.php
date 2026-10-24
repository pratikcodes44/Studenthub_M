<?php
require_once 'bootstrap.php';
require_admin();
$pageTitle = 'Manage Events';
$message = '';
$error = '';
$editEvent = null;
$uploadDir = __DIR__ . '/uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'delete') {
        $stmt = $pdo->prepare('SELECT poster FROM events WHERE id = ?');
        $stmt->execute([$id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($event && $event['poster'] && file_exists($uploadDir . $event['poster'])) unlink($uploadDir . $event['poster']);
        $delete = $pdo->prepare('DELETE FROM events WHERE id = ?');
        $delete->execute([$id]);
        log_action($pdo, 'Deleted event #' . $id);
        header('Location: manage_events.php?status=deleted');
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $eventDate = trim($_POST['event_date'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $poster = trim($_POST['old_poster'] ?? '');

    if ($title === '' || $category === '' || $location === '' || $eventDate === '' || $description === '') {
        $error = 'Please complete all event fields.';
    }
    if (!$error && isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['poster']['tmp_name'];
        $size = $_FILES['poster']['size'];
        $type = mime_content_type($tmp);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($allowed[$type])) {
            $error = 'Poster must be a JPG, PNG or WEBP image.';
        } elseif ($size > 2 * 1024 * 1024) {
            $error = 'Poster must be smaller than 2 MB.';
        } else {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $poster = uniqid('poster_', true) . '.' . $allowed[$type];
            if (!move_uploaded_file($tmp, $uploadDir . $poster)) $error = 'Could not save the poster.';
        }
    }
    if (!$error) {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE events SET title=?, category=?, location=?, event_date=?, description=?, poster=? WHERE id=?');
            $stmt->execute([$title, $category, $location, $eventDate, $description, $poster, $id]);
            log_action($pdo, 'Updated event #' . $id);
            header('Location: manage_events.php?status=updated');
            exit;
        } else {
            $stmt = $pdo->prepare('INSERT INTO events (title, category, location, event_date, description, poster) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $category, $location, $eventDate, $description, $poster]);
            log_action($pdo, 'Added an event');
            header('Location: manage_events.php?status=added');
            exit;
        }
    }
}
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM events WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editEvent = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
$events = $pdo->query('SELECT * FROM events ORDER BY event_date')->fetchAll(PDO::FETCH_ASSOC);
require 'partials/header.php';
?>
<p class="eyebrow">Practical 12</p>
<h1>Manage campus events</h1>
<p class="muted">Add, edit or delete events. Poster files are limited to JPG, PNG or WEBP and 2 MB.</p>
<?php if (isset($_GET['status'])): ?><div class="notice success">Event <?= e($_GET['status']) ?> successfully.</div><?php endif; ?>
<?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<div class="panel">
<h2><?= $editEvent ? 'Edit event' : 'Add an event' ?></h2>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?= (int)($editEvent['id'] ?? 0) ?>">
<input type="hidden" name="old_poster" value="<?= e($editEvent['poster'] ?? '') ?>">
<div class="form-grid">
<div class="form-group">
<label for="title">Event title</label>
<input id="title" name="title" required value="<?= e($editEvent['title'] ?? '') ?>">
</div>
<div class="form-group">
<label for="category">Category</label>
<input id="category" name="category" required value="<?= e($editEvent['category'] ?? 'Technical') ?>">
</div>
<div class="form-group">
<label for="location">Location</label>
<input id="location" name="location" required value="<?= e($editEvent['location'] ?? '') ?>">
</div>
<div class="form-group">
<label for="event_date">Date</label>
<input type="date" id="event_date" name="event_date" required value="<?= e($editEvent['event_date'] ?? '') ?>">
</div>
</div>
<div class="form-group">
<label for="description">Description</label>
<textarea id="description" name="description" required><?= e($editEvent['description'] ?? '') ?></textarea>
</div>
<div class="form-group">
<label for="poster">Poster (optional, max 2 MB)</label>
<input type="file" id="poster" name="poster" accept="image/png,image/jpeg,image/webp">
</div>
<button class="button" type="submit"><?= $editEvent ? 'Save changes' : 'Add event' ?></button><?php if ($editEvent): ?> <a class="button button-outline" href="manage_events.php">Cancel</a><?php endif; ?></form>
</div>
<div class="section-head">
<div>
<h2>Current events</h2>
<p><?= count($events) ?> event(s) in the database.</p>
</div>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Title</th>
<th>Category</th>
<th>Location</th>
<th>Date</th>
<th>Poster</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($events as $event): ?><tr>
<td><?= e($event['title']) ?></td>
<td><?= e($event['category']) ?></td>
<td><?= e($event['location']) ?></td>
<td><?= e($event['event_date']) ?></td>
<td><?php if ($event['poster'] && file_exists($uploadDir . $event['poster'])): ?><img class="poster-preview" src="uploads/<?= e($event['poster']) ?>" alt="Event poster"><?php else: ?>—<?php endif; ?></td>
<td>
<a class="button button-small" href="manage_events.php?edit=<?= (int)$event['id'] ?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete this event?')">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= (int)$event['id'] ?>">
<button class="button button-small" style="background:#c83848" type="submit">Delete</button>
</form>
</td>
</tr><?php endforeach; ?>
</tbody>
</table>
</div>
<?php require 'partials/footer.php'; ?>
