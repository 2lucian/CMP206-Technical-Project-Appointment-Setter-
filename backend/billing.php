<?php

$clientId = "ASYQ-o7hXm-0F-RLjxWzlJBHNHKjEbTFZBpZgkdu0_nMJEnItfk0yHB4t98Po_8E7NlGypt4uCV0x9Sz";
$secret = "EP21RujTAEECoNqaWteDtX_du7A5OOSLxPxIsc6y6FyKRSkkwXJud5HCKlwJb5zrsf00zhx1RjC084Vn";

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

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Accept-Language: en_US"
    ]);

    $result = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($result);

    return $data->access_token;
}

$accessToken = getAccessToken($clientId, $secret);

$orderData = [
    "intent" => "CAPTURE",

    "purchase_units" => [[
        "amount" => [
            "currency_code" => "USD",
            "value" => "10.00"
        ]
    ]],

    "application_context" => [

        "return_url" =>
            "http://localhost/success.php",

        "cancel_url" =>
            "http://localhost/cancel.php"
    ]
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,
    "https://api-m.sandbox.paypal.com/v2/checkout/orders");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS,
    json_encode($orderData));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $accessToken
]);

$result = curl_exec($ch);

curl_close($ch);

$order = json_decode($result, true);

foreach ($order['links'] as $link) {

    if ($link['rel'] === 'approve') {

        echo json_encode([
            "approval_url" => $link['href']
        ]);

        exit;
    }
}
?>