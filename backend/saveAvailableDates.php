<?php

header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: POST");

header("Access-Control-Allow-Headers: Content-Type");

header("Content-Type: application/json");

include 'dbconnect.php';

$rawInput =
    file_get_contents("php://input");

$data =
    json_decode($rawInput, true);

if ($data === null) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON."
    ]);

    exit;
}

if (
    empty($data['doctor_id']) ||
    empty($data['available_date'])
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Doctor ID and date are required."
    ]);

    exit;
}


$stmt = $conn->prepare(
    "INSERT INTO appointment_date (doctor_id, appointment_date) VALUES (?, ?)"
);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "ss",
    $data['doctor_id'],
    $data['available_date']
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" =>
            "Available date saved successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" =>
            "Insert failed: " . $stmt->error
    ]);
}

$stmt->close();

$conn->close();

?>