<?php

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json");

include 'dbconnect.php';

$sql = "
SELECT
    appointments.*,
    patients.first_name,
    patients.last_name
FROM appointments
INNER JOIN patients
ON appointments.patient_id = patients.patient_id
ORDER BY appointment_date ASC
";

$result = $conn->query($sql);

$appointments = [];

if ($result && $result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

        $appointments[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "appointments" => $appointments
]);

$conn->close();

?>