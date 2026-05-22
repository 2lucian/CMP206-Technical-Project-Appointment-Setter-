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

$stmt = $conn->prepare("DELETE FROM {$table} WHERE {$idField} = ?");
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'SQL prepare failed: ' . $conn->error
    ]);
    $conn->close();
    exit;
}

$stmt->bind_param('s', $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        'success' => true,
        'message' => 'User deleted successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No user was deleted. User may not exist.'
    ]);
}

$stmt->close();
$conn->close();
