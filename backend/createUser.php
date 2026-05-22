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

$requiredFields = ['first_name', 'last_name', 'email', 'password', 'phone_number', 'role'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        echo json_encode([
            'success' => false,
            'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.'
        ]);
        exit;
    }
}

$role = strtolower(trim($data['role']));
if ($role === 'patient') {
    if (empty($data['gender']) || empty($data['dob'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Gender and date of birth are required for patients.'
        ]);
        exit;
    }
    $table = 'patients';
    $idField = 'patient_id';
    $requiredFields = ['gender', 'dob'];
} elseif ($role === 'doctor') {
    $table = 'doctors';
    $idField = 'doctor_id';
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid role provided.'
    ]);
    exit;
}

$email = trim($data['email']);
$stmt = $conn->prepare("SELECT email FROM patients WHERE email = ? UNION SELECT email FROM doctors WHERE email = ?");
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'SQL prepare failed: ' . $conn->error
    ]);
    exit;
}
$stmt->bind_param('ss', $email, $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'This email is already in use.'
    ]);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$userId = uniqid($role === 'patient' ? 'pat_' : 'doc_', true);
$passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

if ($role === 'patient') {
    $insertSql = "INSERT INTO patients (patient_id, first_name, last_name, email, password, phone_number, gender, dob) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'SQL prepare failed: ' . $conn->error
        ]);
        $conn->close();
        exit;
    }
    $stmt->bind_param(
        'ssssssss',
        $userId,
        $data['first_name'],
        $data['last_name'],
        $email,
        $passwordHash,
        $data['phone_number'],
        $data['gender'],
        $data['dob']
    );
} else {
    $insertSql = "INSERT INTO doctors (doctor_id, first_name, last_name, email, password, phone_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'SQL prepare failed: ' . $conn->error
        ]);
        $conn->close();
        exit;
    }
    $stmt->bind_param(
        'ssssss',
        $userId,
        $data['first_name'],
        $data['last_name'],
        $email,
        $passwordHash,
        $data['phone_number']
    );
}

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'User created successfully.',
        'reload' => true
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Insert failed: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
