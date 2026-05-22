<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'dbconnect.php';

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if ($data === null) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON received.'
    ]);
    exit;
}

if (empty($data['user_id']) || empty($data['role'])) {
    echo json_encode([
        'success' => false,
        'message' => 'user_id and role are required.'
    ]);
    exit;
}

$userId = $data['user_id'];
$role = strtolower(trim($data['role']));

if ($role === 'patient') {
    $table = 'patients';
    $idField = 'patient_id';
} elseif ($role === 'doctor') {
    $table = 'doctors';
    $idField = 'doctor_id';
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Unknown role provided.'
    ]);
    exit;
}

$fields = [];
$params = [];
$types = '';

if (!empty($data['name'])) {
    $name = trim($data['name']);
    $parts = preg_split('/\s+/', $name, 2);
    $firstName = $parts[0];
    $lastName = isset($parts[1]) ? $parts[1] : '';
    $fields[] = 'first_name = ?';
    $types .= 's';
    $params[] = $firstName;
    $fields[] = 'last_name = ?';
    $types .= 's';
    $params[] = $lastName;
}

if (!empty($data['email'])) {
    $email = trim($data['email']);
    $checkStmt = $conn->prepare("SELECT {$idField} FROM {$table} WHERE email = ? AND {$idField} <> ?");
    if (!$checkStmt) {
        echo json_encode([
            'success' => false,
            'message' => 'SQL prepare failed: ' . $conn->error
        ]);
        $conn->close();
        exit;
    }
    $checkStmt->bind_param('ss', $email, $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    if ($result && $result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'This email is already in use.'
        ]);
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    $fields[] = 'email = ?';
    $types .= 's';
    $params[] = $email;
}

if (!empty($data['password'])) {
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
    $fields[] = 'password = ?';
    $types .= 's';
    $params[] = $passwordHash;
}

if (count($fields) === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'No update fields were provided.'
    ]);
    $conn->close();
    exit;
}

$updateSql = 'UPDATE ' . $table . ' SET ' . implode(', ', $fields) . ' WHERE ' . $idField . ' = ?';
$types .= 's';
$params[] = $userId;

$stmt = $conn->prepare($updateSql);
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'SQL prepare failed: ' . $conn->error
    ]);
    $conn->close();
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'User updated successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Update failed: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
