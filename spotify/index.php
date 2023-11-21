<?php
$client_id = '565023feab57447c808621efa57f1512';
$redirect_uri = 'https://cantio.live/spotify/callback.php';
$scope = 'user-top-read';

$auth_url = "https://accounts.spotify.com/authorize?response_type=code&client_id={$client_id}&scope={$scope}&redirect_uri={$redirect_uri}";

echo "<button onclick=\"window.location.href='{$auth_url}'\">Login with Spotify</button>";
?>