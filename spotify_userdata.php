<?php
session_start();
$accessToken = $_SESSION['access_token'];
$headers = ['Authorization: Bearer ' . $accessToken];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/top/tracks?time_range=short_term&limit=5');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;  // This will output the user's Spotify data in JSON format.
?>