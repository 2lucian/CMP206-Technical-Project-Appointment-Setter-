<?php

header("Access-Control-Allow-Origin: ");
header("Content-Type: application/json");

include 'dbconnect.php';

$response = [
    "success" => false,
    "total_users" => 0,
    "total_appointments" => 0
];

/*
|--------------------------------------------------------------------------
| TOTAL USERS
|--------------------------------------------------------------------------
| Count patients + doctors + admins
*/

$userCount = 0;

$tables = ["patients", "doctors", "admin"];

foreach ($tables as $table) {

    $sql = "SELECT COUNT(*) AS total FROM $table";

    $result = $conn->query($sql);

    if ($result) {

        $row = $result->fetch_assoc();

        $userCount += (int)$row['total'];
    }
}

/*
|--------------------------------------------------------------------------
| TOTAL APPOINTMENTS
|--------------------------------------------------------------------------
*/

$appointmentSql =
    "SELECT COUNT(*) AS total FROM appointments";

$appointmentResult =
    $conn->query($appointmentSql);

$appointmentCount = 0;

if ($appointmentResult) {

    $row =
        $appointmentResult->fetch_assoc();

    $appointmentCount =
        (int)$row['total'];
}

$response['success'] = true;
$response['total_users'] = $userCount;
$response['total_appointments'] = $appointmentCount;

echo json_encode($response);

$conn->close();

?>