<?php
$CLIENT_ID = 'e6f6744c543743be87a5cc703087931c';
$CLIENT_SECRET = '908bf89c013c4ed1994bfec220c7398d';
$REDIRECT_URI = 'https://cantio.live/callback.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    
    $tokenURL = 'https://accounts.spotify.com/api/token';
    $headers = ['Authorization: Basic ' . base64_encode($CLIENT_ID . ':' . $CLIENT_SECRET)];
    $content = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $REDIRECT_URI
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenURL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    $response = curl_exec($ch);
    curl_close($ch);

    $tokens = json_decode($response, true);
    session_start();
    $_SESSION['access_token'] = $tokens['access_token'];

    // Redirect user to the frontend page that will display their Spotify data
    header('Location: http://yourwebsite.com/userdata.html');
    exit();
}
?>