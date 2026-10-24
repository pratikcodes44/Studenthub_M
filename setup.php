<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'studentwdf_db';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) NOT NULL DEFAULT 'student',
        phone VARCHAR(20) DEFAULT '',
        department VARCHAR(80) DEFAULT '',
        year_no VARCHAR(20) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        phone VARCHAR(20) DEFAULT '',
        department VARCHAR(80) DEFAULT '',
        year_no VARCHAR(20) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(150) NOT NULL,
        category VARCHAR(80) NOT NULL,
        location VARCHAR(150) NOT NULL,
        event_date DATE NOT NULL,
        description TEXT NOT NULL,
        poster VARCHAR(255) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY one_registration (user_id, event_id),
        CONSTRAINT fk_reg_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_reg_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        subject VARCHAR(150) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_email VARCHAR(150) NOT NULL,
        action_performed VARCHAR(255) NOT NULL,
        ip_address VARCHAR(50) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute(['admin@studentwdf.local']);
    if (!$check->fetch()) {
        $hash = password_hash('Admin@12345', PASSWORD_DEFAULT);
        $add = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, department, year_no) VALUES (?, ?, ?, 'admin', 'CE', 'N/A')");
        $add->execute(['StudentWDF Admin', 'admin@studentwdf.local', $hash]);
    }

    $eventCount = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
    if ($eventCount === 0) {
        $events = [
            ['Web Development Workshop', 'Technical', 'Computer Lab 1', '2026-11-05', 'A beginner-friendly session on building websites.'],
            ['Campus Coding Challenge', 'Competition', 'Seminar Hall', '2026-11-12', 'Solve small programming problems with your team.'],
            ['Student Club Meet', 'Campus', 'Main Auditorium', '2026-11-20', 'Meet student clubs and learn about upcoming activities.']
        ];
        $addEvent = $pdo->prepare('INSERT INTO events (title, category, location, event_date, description) VALUES (?, ?, ?, ?, ?)');
        foreach ($events as $event) {
            $addEvent->execute($event);
        }
    }

    $studentCount = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    if ($studentCount === 0) {
        $students = [
            ['Aarav Patel', 'aarav@example.com', '9876543210', 'CE', '1'],
            ['Riya Shah', 'riya@example.com', '9876501234', 'IT', '1'],
            ['Dev Mehta', 'dev@example.com', '9876512345', 'CSE', '2']
        ];
        $addStudent = $pdo->prepare('INSERT INTO students (full_name, email, phone, department, year_no) VALUES (?, ?, ?, ?, ?)');
        foreach ($students as $student) {
            $addStudent->execute($student);
        }
    }

    $message = 'Database setup completed successfully. You can now open the StudentWDF website.';
} catch (PDOException $e) {
    $message = 'Setup failed. Check that MySQL is running and the database username/password are correct.';
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentWDF Setup</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="setup-page">
<main class="container narrow">
    <section class="panel">
        <p class="eyebrow">StudentWDF</p>
        <h1>Database Setup</h1>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php if (isset($error)): ?><p class="muted small">Technical detail: <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <a class="button" href="index.php">Open website</a>
    </section>
</main>
</body>
</html>
