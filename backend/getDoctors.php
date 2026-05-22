<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'dbconnect.php';

$sql = "SELECT doctor_id, CONCAT(first_name, ' ', last_name) AS doctor_name FROM doctors ORDER BY first_name, last_name";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Database query failed: " . $conn->error
    ]);
    $conn->close();
    exit;
}

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

echo json_encode([
    "success" => true,
    "doctors" => $doctors
]);

$conn->close();
?>