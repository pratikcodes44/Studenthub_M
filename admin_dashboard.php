<?php
require_once 'bootstrap.php';
require_admin();
log_action($pdo, 'Viewed admin dashboard');
$pageTitle = 'Admin Dashboard';
$userCount = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$studentCount = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$eventCount = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$messageCount = (int)$pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$recentStudents = $pdo->query('SELECT full_name, email, department, created_at FROM students ORDER BY id DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
$logs = $pdo->query('SELECT user_email, action_performed, ip_address, created_at FROM audit_logs ORDER BY id DESC LIMIT 8')->fetchAll(PDO::FETCH_ASSOC);
require 'partials/header.php';
?>
<p class="eyebrow">Practical 14</p>
<h1>Admin dashboard</h1>
<p class="muted">Welcome, <?= e($_SESSION['name']) ?>. Here is a quick view of the current database.</p>
<section class="grid">
    <div class="stat-card">
<span>Total accounts</span>
<strong><?= $userCount ?></strong>
</div>
    <div class="stat-card">
<span>Student records</span>
<strong><?= $studentCount ?></strong>
</div>
    <div class="stat-card">
<span>Events</span>
<strong><?= $eventCount ?></strong>
</div>
    <div class="stat-card">
<span>Contact messages</span>
<strong><?= $messageCount ?></strong>
</div>
</section>
<div class="section-head">
<div>
<h2>Admin tools</h2>
<p>Pages available to the admin account.</p>
</div>
</div>
<section class="grid">
<article class="card">
<h3>Student records</h3>
<p>Search, add, edit or delete student rows.</p>
<a class="button button-small" href="admin.php">Manage students</a>
</article>
<article class="card">
<h3>Campus events</h3>
<p>Manage events and optional poster images.</p>
<a class="button button-small" href="manage_events.php">Manage events</a>
</article>
<article class="card">
<h3>Fetch API records</h3>
<p>Try CRUD operations without reloading the page.</p>
<a class="button button-small" href="ajax_students.php">Open API page</a>
</article>
</section>
<div class="section-head">
<div>
<h2>Recent students</h2>
<p>Latest five student records.</p>
</div>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Created</th>
</tr>
</thead>
<tbody><?php foreach ($recentStudents as $student): ?><tr>
<td><?= e($student['full_name']) ?></td>
<td><?= e($student['email']) ?></td>
<td><?= e($student['department']) ?></td>
<td><?= e($student['created_at']) ?></td>
</tr><?php endforeach; ?><?php if (!$recentStudents): ?><tr>
<td colspan="4">No student records yet.</td>
</tr><?php endif; ?></tbody>
</table>
</div>
<div class="section-head">
<div>
<h2>Audit log</h2>
<p>Recent actions recorded in the database.</p>
</div>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Email</th>
<th>Action</th>
<th>IP address</th>
<th>Date</th>
</tr>
</thead>
<tbody><?php foreach ($logs as $log): ?><tr>
<td><?= e($log['user_email']) ?></td>
<td><?= e($log['action_performed']) ?></td>
<td><?= e($log['ip_address']) ?></td>
<td><?= e($log['created_at']) ?></td>
</tr><?php endforeach; ?><?php if (!$logs): ?><tr>
<td colspan="4">No audit activity yet.</td>
</tr><?php endif; ?></tbody>
</table>
</div>
<?php require 'partials/footer.php'; ?>
