<?php
session_start();

$client_id = '446002b2d4434bff9fec8de86cee969d';
$client_secret = '645c16a2a1bd4a1990df607ccc7e1b17';
$redirect_uri = 'https://cantio.live/spotify/callback.php';

$code = $_GET['code'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=authorization_code&code={$code}&redirect_uri={$redirect_uri}&client_id={$client_id}&client_secret={$client_secret}");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

$response = curl_exec($ch);
$token_data = json_decode($response, true);
$access_token = $token_data['access_token'];

$_SESSION['access_token'] = $access_token;

header('Location: display.php');
?>