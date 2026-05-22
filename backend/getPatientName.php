<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'dbconnect.php';

if (!isset($_GET['patient_id']) || empty(trim($_GET['patient_id']))) {
    echo json_encode([
        'success' => false,
        'message' => 'Patient ID is required.'
    ]);
    $conn->close();
    exit;
}

$patientId = trim($_GET['patient_id']);

$stmt = $conn->prepare("SELECT first_name, last_name FROM patients WHERE patient_id = ?");
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to prepare query: ' . $conn->error
    ]);
    $conn->close();
    exit;
}

$stmt->bind_param('s', $patientId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows !== 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Patient not found.'
    ]);
    $stmt->close();
    $conn->close();
    exit;
}

$patient = $result->fetch_assoc();
$stmt->close();
$conn->close();

$fullName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));

echo json_encode([
    'success' => true,
    'first_name' => $patient['first_name'],
    'last_name' => $patient['last_name'],
    'full_name' => $fullName !== '' ? $fullName : null
]);
