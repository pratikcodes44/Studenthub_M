<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function require_admin() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: login.php?error=admin');
        exit;
    }
}

function log_action($pdo, $action) {
    if (!isset($_SESSION['email'])) {
        return;
    }
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $pdo->prepare(
        'INSERT INTO audit_logs (user_email, action_performed, ip_address) VALUES (?, ?, ?)'
    );
    $stmt->execute([$_SESSION['email'], $action, $ip]);
}
?>
