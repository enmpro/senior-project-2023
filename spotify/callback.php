<?php
session_start();

$client_id = '565023feab57447c808621efa57f1512';
$client_secret = 'df726856a4f1434b8fd64adef18649e9';
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

header('Location: ../test.php');
?>