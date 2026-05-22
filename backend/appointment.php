<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'dbconnect.php';

$rawInput = file_get_contents("php://input");

$data = json_decode($rawInput, true);

error_log("RAW INPUT: " . $rawInput);

if ($data === null) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON received."
    ]);

    exit;
}

$requiredFields = [
    'patient_id',
    'doctor_id',
    'appointment_date',
    'appointment_time',
    'service'
];

foreach ($requiredFields as $field) {

    if (empty($data[$field])) {

        echo json_encode([
            "success" => false,
            "message" => ucfirst(str_replace('_', ' ', $field)) . " is required."
        ]);

        $conn->close();
        exit;
    }
}

$appointmentId = uniqid('appt_', true);

$duration = !empty($data['appointment_duration'])
    ? $data['appointment_duration']
    : '00:30:00';

$notes = !empty($data['appointment_notes'])
    ? $data['appointment_notes']
    : '';

$stmt = $conn->prepare(
    "INSERT INTO appointments
    (
        appointment_id,
        patient_id,
        doctor_id,
        appointment_date,
        appointment_time,
        appointment_duration,
        appointment_notes,
        service
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);

    $conn->close();
    exit;
}

$stmt->bind_param(
    "ssssssss",
    $appointmentId,
    $data['patient_id'],
    $data['doctor_id'],
    $data['appointment_date'],
    $data['appointment_time'],
    $duration,
    $notes,
    $data['service']
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Appointment saved successfully.",
        "appointment_id" => $appointmentId
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Insert failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>