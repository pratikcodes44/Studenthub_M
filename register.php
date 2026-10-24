<?php
require_once 'bootstrap.php';
$pageTitle = 'Register';
$errors = [];
$success = '';
$name = '';
$email = '';
$phone = '';
$department = 'CE';
$yearNo = '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $yearNo = trim($_POST['year_no'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($name) < 3) $errors[] = 'Name must contain at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if ($phone !== '' && !preg_match('/^[0-9]{10}$/', $phone)) $errors[] = 'Mobile number should contain 10 digits.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!in_array($department, ['CE', 'IT', 'CSE'], true)) $errors[] = 'Select a department.';
    if (!in_array($yearNo, ['1', '2', '3', '4'], true)) $errors[] = 'Select your year.';
    if (!isset($_POST['terms'])) $errors[] = 'Please accept the terms.';

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $errors[] = 'This email is already registered. Please use another email or log in.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, phone, department, year_no) VALUES (?, ?, ?, 'student', ?, ?, ?)");
            $stmt->execute([$name, $email, $hash, $phone, $department, $yearNo]);
            $student = $pdo->prepare('INSERT INTO students (full_name, email, phone, department, year_no) VALUES (?, ?, ?, ?, ?)');
            $student->execute([$name, $email, $phone, $department, $yearNo]);
            $success = 'Registration completed. You can now log in.';
            $name = $email = $phone = '';
        }
    }
}
require 'partials/header.php';
?>
<section class="panel narrow" style="margin:0 auto">
    <p class="eyebrow">Join StudentWDF</p>
<h1>Create an account</h1>
<p class="muted">Enter your student details below.</p>
    <?php if ($success): ?><div class="notice success"><?= e($success) ?> <a href="login.php">Log in</a>
</div><?php endif; ?>
    <?php if ($errors): ?><div class="notice error">
<strong>Please correct these details:</strong>
<ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
</div><?php endif; ?>
    <div id="form-error" class="notice error" hidden>
</div>
    <form id="register-form" method="post" action="register.php" novalidate>
        <div class="form-group">
<label for="name">Full name</label>
<input id="name" name="name" value="<?= e($name) ?>" required>
</div>
        <div class="form-group">
<label for="email">Email address</label>
<input type="email" id="email" name="email" value="<?= e($email) ?>" required>
</div>
        <div class="form-grid">
            <div class="form-group">
<label for="phone">Mobile (optional)</label>
<input id="phone" name="phone" value="<?= e($phone) ?>" inputmode="numeric" maxlength="10">
</div>
            <div class="form-group">
<label for="department">Department</label>
<select id="department" name="department">
<option value="CE" <?= $department==='CE'?'selected':'' ?>>Computer Engineering</option>
<option value="IT" <?= $department==='IT'?'selected':'' ?>>Information Technology</option>
<option value="CSE" <?= $department==='CSE'?'selected':'' ?>>Computer Science</option>
</select>
</div>
        </div>
        <div class="form-grid">
            <div class="form-group">
<label for="year_no">Year</label>
<select id="year_no" name="year_no">
<option value="1" <?= $yearNo==='1'?'selected':'' ?>>1st Year</option>
<option value="2" <?= $yearNo==='2'?'selected':'' ?>>2nd Year</option>
<option value="3" <?= $yearNo==='3'?'selected':'' ?>>3rd Year</option>
<option value="4" <?= $yearNo==='4'?'selected':'' ?>>4th Year</option>
</select>
</div>
            <div class="form-group">
<label for="password">Password</label>
<input type="password" id="password" name="password" required minlength="8">
</div>
        </div>
        <div class="form-group">
<label for="confirm_password">Confirm password</label>
<input type="password" id="confirm_password" name="confirm_password" required>
</div>
        <div class="form-group">
<label>
<input type="checkbox" id="terms" name="terms" style="width:auto"> I agree to the terms</label>
</div>
        <button class="button" type="submit">Create account</button>
    </form>
</section>
<script src="assets/validation.js"></script>
<?php require 'partials/footer.php'; ?>
