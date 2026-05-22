<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'dbconnect.php';

if (!isset($_GET['patient_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Patient ID missing."
    ]);

    exit;
}

$patientId = $_GET['patient_id'];

$stmt = $conn->prepare(

    "SELECT
        appointments.appointment_date,
        appointments.appointment_time,
        appointments.service,
        doctors.first_name,
        doctors.last_name
    FROM appointments

    INNER JOIN doctors
        ON appointments.doctor_id = doctors.doctor_id

    WHERE appointments.patient_id = ?

    ORDER BY appointments.appointment_date ASC"

);

$stmt->bind_param("s", $patientId);

$stmt->execute();

$result = $stmt->get_result();

$appointments = [];

while ($row = $result->fetch_assoc()) {

    $appointments[] = $row;
}

echo json_encode([
    "success" => true,
    "appointments" => $appointments
]);

$stmt->close();
$conn->close();

?>