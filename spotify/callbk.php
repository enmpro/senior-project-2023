<?php
session_start();
require '../vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '565023feab57447c808621efa57f1512',
    'df726856a4f1434b8fd64adef18649e9',
    'https://cantio.live/spotify/callbk.php'
);

$state = $_GET['state'];

// Fetch the stored state value from somewhere. A session for example
$storedState = $_SESSION['state'];

if ($state !== $storedState) {
    // The state returned isn't the same as the one we've stored, we shouldn't continue
    die('State mismatch');
}

// Request a access token using the code from Spotify
$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

$_SESSION['accessToken'] = $accessToken;
$_SESSION['refreshToken'] = $refreshToken;

// Store the access and refresh tokens somewhere. In a session for example

// Send the user along and fetch some data!
header('Location: app.php');
die();

?>