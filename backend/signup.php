<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'dbconnect.php';

$rawInput = file_get_contents("php://input");

$data = json_decode($rawInput, true);

error_log("RAW INPUT: " . $rawInput);
error_log("DECODED DATA: " . print_r($data, true));

if ($data === null) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON received."
    ]);

    exit;
}

$requiredFields = [
    'first_name',
    'last_name',
    'email',
    'password',
    'phone_number',
    'gender',
    'dob'
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

$email = $data['email'];

$stmt = $conn->prepare(
    "SELECT patient_id FROM patients WHERE email = ?"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "This email is already in use."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}

$stmt->close();

$patientId = uniqid("pat_", true);

$passwordHash = password_hash(
    $data['password'],
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "INSERT INTO patients
    (
        patient_id,
        first_name,
        last_name,
        email,
        password,
        phone_number,
        gender,
        dob
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "SQL prepare failed: " . $conn->error
    ]);

    $conn->close();

    exit;
}

$stmt->bind_param(
    "ssssssss",
    $patientId,
    $data['first_name'],
    $data['last_name'],
    $data['email'],
    $passwordHash,
    $data['phone_number'],
    $data['gender'],
    $data['dob']
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Account created successfully.",
        "patient_id" => $patientId
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