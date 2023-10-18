<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    echo json_encode(['error' => 'No access token found.']);
    exit();
}

$accessToken = $_SESSION['access_token'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 401) {  // Token has expired or is invalid
    // You can implement refresh token logic here if needed

    // For simplicity, in this example, we're just sending an error:
    echo json_encode(['error' => 'Token expired. Please authenticate again.']);
    exit();
}

echo $response;
?>
