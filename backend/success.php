<?php

$clientId = "ASYQ-o7hXm-0F-RLjxWzlJBHNHKjEbTFZBpZgkdu0_nMJEnItfk0yHB4t98Po_8E7NlGypt4uCV0x9Sz";
$secret = "EP21RujTAEECoNqaWteDtX_du7A5OOSLxPxIsc6y6FyKRSkkwXJud5HCKlwJb5zrsf00zhx1RjC084Vn";

$orderID = $_GET['token'];

function getAccessToken($clientId, $secret) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,
        "https://api-m.sandbox.paypal.com/v1/oauth2/token");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_USERPWD,
        $clientId . ":" . $secret);

    curl_setopt($ch, CURLOPT_POSTFIELDS,
        "grant_type=client_credentials");

    curl_setopt($ch, CURLOPT_POST, true);

    $result = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($result);

    return $data->access_token;
}

$accessToken = getAccessToken($clientId, $secret);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,
    "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $accessToken
]);

$result = curl_exec($ch);

curl_close($ch);

$response = json_decode($result, true);

$success =
    isset($response['status']) &&
    $response['status'] === 'COMPLETED';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Result</title>
</head>
<body>

<h2>Returning to website...</h2>

<script>

<?php if ($success): ?>

alert("Payment successful!");

window.location.href = "/Frontend/Home.html";

<?php else: ?>

alert("Payment failed.");

window.location.href = "/Frontend/Home.html";

<?php endif; ?>

</script>

</body>
</html>