<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'dbconnect.php';

$sql = "SELECT patient_id AS user_id, CONCAT(first_name, ' ', last_name) AS name, email, password, 'patient' AS role
        FROM patients
        UNION ALL
        SELECT doctor_id AS user_id, CONCAT(first_name, ' ', last_name) AS name, email, password, 'doctor' AS role
        FROM doctors
        ORDER BY role, name";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Database query failed: " . $conn->error
    ]);
    $conn->close();
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    "success" => true,
    "users" => $users
]);

$conn->close();
