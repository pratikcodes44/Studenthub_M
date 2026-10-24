<?php
require_once 'bootstrap.php';
require_admin();
$pageTitle = 'Manage Students';
$message = '';
$errors = [];
$editStudent = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $yearNo = trim($_POST['year_no'] ?? '');

    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
        $stmt->execute([$id]);
        log_action($pdo, 'Deleted student #' . $id);
        header('Location: admin.php?status=deleted');
        exit;
    }

    if (strlen($name) < 3) $errors[] = 'Enter a name with at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if ($phone !== '' && !preg_match('/^[0-9]{10}$/', $phone)) $errors[] = 'Phone should contain 10 digits.';
    if (!$errors) {
        if ($action === 'update') {
            $stmt = $pdo->prepare('UPDATE students SET full_name=?, email=?, phone=?, department=?, year_no=? WHERE id=?');
            $stmt->execute([$name, $email, $phone, $department, $yearNo, $id]);
            log_action($pdo, 'Updated student #' . $id);
            header('Location: admin.php?status=updated');
            exit;
        } else {
            $stmt = $pdo->prepare('INSERT INTO students (full_name, email, phone, department, year_no) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $email, $phone, $department, $yearNo]);
            log_action($pdo, 'Added student record');
            header('Location: admin.php?status=added');
            exit;
        }
    }
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editStudent = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE full_name LIKE ? OR email LIKE ? ORDER BY id DESC');
    $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $students = $pdo->query('SELECT * FROM students ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
}
require 'partials/header.php';
?>
<p class="eyebrow">Practical 11</p>
<h1>Manage student records</h1>
<p class="muted">Add, edit, search and delete sample student information.</p>
<?php if (isset($_GET['status'])): ?><div class="notice success">Record <?= e($_GET['status']) ?> successfully.</div><?php endif; ?>
<?php if ($errors): ?><div class="notice error">
<ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
</div><?php endif; ?>
<div class="grid-2" style="align-items:start">
<section class="panel">
<h2><?= $editStudent ? 'Edit student' : 'Add student' ?></h2>
<form method="post">
<input type="hidden" name="action" value="<?= $editStudent ? 'update' : 'add' ?>">
<input type="hidden" name="id" value="<?= (int)($editStudent['id'] ?? 0) ?>">
<div class="form-group">
<label for="full_name">Full name</label>
<input id="full_name" name="full_name" required value="<?= e($editStudent['full_name'] ?? ($_POST['full_name'] ?? '')) ?>">
</div>
<div class="form-group">
<label for="email">Email</label>
<input type="email" id="email" name="email" required value="<?= e($editStudent['email'] ?? ($_POST['email'] ?? '')) ?>">
</div>
<div class="form-group">
<label for="phone">Phone</label>
<input id="phone" name="phone" maxlength="10" value="<?= e($editStudent['phone'] ?? ($_POST['phone'] ?? '')) ?>">
</div>
<div class="form-grid">
<div class="form-group">
<label for="department">Department</label>
<select id="department" name="department">
<option value="CE" <?= (($editStudent['department'] ?? ($_POST['department'] ?? 'CE')) === 'CE') ? 'selected' : '' ?>>CE</option>
<option value="IT" <?= (($editStudent['department'] ?? ($_POST['department'] ?? 'CE')) === 'IT') ? 'selected' : '' ?>>IT</option>
<option value="CSE" <?= (($editStudent['department'] ?? ($_POST['department'] ?? 'CE')) === 'CSE') ? 'selected' : '' ?>>CSE</option>
</select>
</div>
<div class="form-group">
<label for="year_no">Year</label>
<select id="year_no" name="year_no">
<option value="1" <?= (($editStudent['year_no'] ?? ($_POST['year_no'] ?? '1')) === '1') ? 'selected' : '' ?>>1</option>
<option value="2" <?= (($editStudent['year_no'] ?? ($_POST['year_no'] ?? '1')) === '2') ? 'selected' : '' ?>>2</option>
<option value="3" <?= (($editStudent['year_no'] ?? ($_POST['year_no'] ?? '1')) === '3') ? 'selected' : '' ?>>3</option>
<option value="4" <?= (($editStudent['year_no'] ?? ($_POST['year_no'] ?? '1')) === '4') ? 'selected' : '' ?>>4</option>
</select>
</div>
</div>
<button class="button" type="submit"><?= $editStudent ? 'Save changes' : 'Add student' ?></button><?php if ($editStudent): ?> <a class="button button-outline" href="admin.php">Cancel</a><?php endif; ?>
</form>
</section>
<section>
<div class="panel">
<h2>Search records</h2>
<form class="inline-form" method="get">
<div class="form-group">
<label for="search">Name or email</label>
<input id="search" name="search" value="<?= e($search) ?>" placeholder="Search...">
</div>
<button class="button button-small">Search</button>
<a class="button button-outline button-small" href="admin.php">Reset</a>
</form>
</div>
</section>
</div>
<div class="section-head">
<div>
<h2>Student list</h2>
<p><?= count($students) ?> record(s)</p>
</div>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Department</th>
<th>Year</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($students as $student): ?><tr>
<td><?= (int)$student['id'] ?></td>
<td><?= e($student['full_name']) ?></td>
<td><?= e($student['email']) ?></td>
<td><?= e($student['phone']) ?></td>
<td><?= e($student['department']) ?></td>
<td><?= e($student['year_no']) ?></td>
<td>
<a class="button button-small" href="admin.php?edit=<?= (int)$student['id'] ?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete this record?')">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= (int)$student['id'] ?>">
<button class="button button-small" style="background:#c83848" type="submit">Delete</button>
</form>
</td>
</tr><?php endforeach; ?>
<?php if (!$students): ?><tr>
<td colspan="7">No student records found.</td>
</tr><?php endif; ?>
</tbody>
</table>
</div>
<?php require 'partials/footer.php'; ?>
