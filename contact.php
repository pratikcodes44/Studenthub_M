<?php
require_once 'bootstrap.php';
$pageTitle = 'Contact';
$errors = [];
$success = '';
$name = $email = $subject = $messageText = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $messageText = trim($_POST['message'] ?? '');

    if (strlen($name) < 3) $errors[] = 'Please enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if ($subject === '') $errors[] = 'Please enter a subject.';
    if ($messageText === '') $errors[] = 'Please write a message.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $subject, $messageText]);
        $record = ['time' => date('Y-m-d H:i:s'), 'name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $messageText];
        $csv = fopen(__DIR__ . '/storage/contact_messages.csv', 'a');
        if ($csv) { fputcsv($csv, array_values($record)); fclose($csv); }
        $jsonPath = __DIR__ . '/storage/contact_messages.json';
        $saved = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
        if (!is_array($saved)) $saved = [];
        $saved[] = $record;
        file_put_contents($jsonPath, json_encode($saved, JSON_PRETTY_PRINT));
        $success = 'Thanks! Your message has been saved.';
        $name = $email = $subject = $messageText = '';
    }
}
require 'partials/header.php';
?>
<section class="panel narrow" style="margin:0 auto">
<p class="eyebrow">Get in touch</p>
<h1>Contact us</h1>
<p class="muted">Send a query or share your feedback.</p>
<?php if ($success): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="notice error">
<ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
</div><?php endif; ?>
<form method="post" action="contact.php">
    <div class="form-group">
<label for="name">Full name</label>
<input id="name" name="name" value="<?= e($name) ?>" required>
</div>
    <div class="form-group">
<label for="email">Email address</label>
<input type="email" id="email" name="email" value="<?= e($email) ?>" required>
</div>
    <div class="form-group">
<label for="subject">Subject</label>
<input id="subject" name="subject" value="<?= e($subject) ?>" required>
</div>
    <div class="form-group">
<label for="message">Message</label>
<textarea id="message" name="message" required><?= e($messageText) ?></textarea>
</div>
    <button class="button" type="submit">Send message</button>
</form>
</section>
<?php require 'partials/footer.php'; ?>
