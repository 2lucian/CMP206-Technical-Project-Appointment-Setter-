<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'dbconnect.php';

if (!isset($_GET['doctor_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Doctor ID missing."
    ]);

    exit;
}

$doctorId = $_GET['doctor_id'];

$stmt = $conn->prepare(
    "SELECT appointment_date
     FROM appointment_date
     WHERE doctor_id = ?
     ORDER BY appointment_date ASC"
);

$stmt->bind_param("s", $doctorId);

$stmt->execute();

$result = $stmt->get_result();

$dates = [];

while ($row = $result->fetch_assoc()) {

    $dates[] = $row['appointment_date'];
}

echo json_encode([
    "success" => true,
    "dates" => $dates
]);

$stmt->close();
$conn->close();

?>