<?php
require_once 'bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Please log in as admin.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $students = $pdo->query('SELECT id, full_name, email, department, year_no FROM students ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $students]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?? [];
if ($method === 'POST') {
    $name = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $department = trim($data['department'] ?? 'CE');
    $yearNo = trim($data['year_no'] ?? '1');
    if (strlen($name) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Enter a name and valid email.']);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO students (full_name, email, department, year_no) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $department, $yearNo]);
    log_action($pdo, 'Added student through API');
    echo json_encode(['success' => true, 'message' => 'Student added.']);
    exit;
}

if ($method === 'PUT') {
    $id = (int)($data['id'] ?? 0);
    $name = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $department = trim($data['department'] ?? 'CE');
    $yearNo = trim($data['year_no'] ?? '1');
    if ($id < 1 || strlen($name) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Check the ID, name and email.']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE students SET full_name=?, email=?, department=?, year_no=? WHERE id=?');
    $stmt->execute([$name, $email, $department, $yearNo, $id]);
    log_action($pdo, 'Updated student through API');
    echo json_encode(['success' => true, 'message' => 'Student updated.']);
    exit;
}

if ($method === 'DELETE') {
    $id = (int)($data['id'] ?? 0);
    if ($id < 1) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'A valid student ID is required.']);
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM students WHERE id=?');
    $stmt->execute([$id]);
    log_action($pdo, 'Deleted student through API');
    echo json_encode(['success' => true, 'message' => 'Student deleted.']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not supported.']);
?>
