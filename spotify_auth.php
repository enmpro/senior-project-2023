<?php
$CLIENT_ID = 'e6f6744c543743be87a5cc703087931c';
$REDIRECT_URI = 'https://cantio.live/spotify_callback.php';

$AUTH_URL = "https://accounts.spotify.com/authorize?client_id={$CLIENT_ID}&response_type=code&redirect_uri=" . urlencode($REDIRECT_URI);

header('Location: ' . $AUTH_URL);
exit();
?>


