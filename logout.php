<?php
require_once 'bootstrap.php';
if (isset($_SESSION['email'])) {
    log_action($pdo, 'Logged out');
}
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
?>
