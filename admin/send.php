<?php
$endpoint = 'https://api.tiaraconnect.io/api/messaging/sendsms';
$apiKey = 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIzNTEiLCJvaWQiOjM1MSwidWlkIjoiN2Y5ZGQ1ZmMtM2QwMi00ZGZiLTg1YjItY2FjMDBlYjU0NDhkIiwiYXBpZCI6MjQxLCJpYXQiOjE3MTExOTQyMTAsImV4cCI6MjA1MTE5NDIxMH0._BW3-yd5JJmAnRsL_trguFXmTLKFmz_a4EAJVmoIk7H66Lpccj3uKiwuTJjgYoxKLU6ZH0EhAC3pkDU2wQcPXQ';
$from = 'TIARACONECT';
$message = 'Test sms';
$to = '254796471436';

sendSMS($endpoint, $apiKey, $to, $from, $message);

function sendSMS($endpoint, $apiKey, $to, $from, $message)
{
    $request = [
        'to' => $to,
        'from' => $from,
        'message' => $message
    ];
    $requestBody = json_encode($request);

    error_log("request|msisdn: $to|request: $requestBody | url: $endpoint");

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $requestBody,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
    ]);

    $response_body = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($response_body === false) {
        error_log('Curl failed: ' . curl_error($curl));
    } elseif ($http_status !== 200) {
        error_log('HTTP Error: ' . $http_status . ', Response: ' . $response_body);
    } else {
        error_log("request|msisdn: $to|response: $response_body | url: $endpoint");
    }

    curl_close($curl);
}
?>
