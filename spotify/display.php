<?php
session_start();
$access_token = $_SESSION['access_token'];

// Fetch Top Artists
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/top/artists?limit=20');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}"));
$response = curl_exec($ch);
$artists = json_decode($response, true)['items'];

// Fetch Top Tracks
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/top/tracks?limit=20');
$response = curl_exec($ch);
$tracks = json_decode($response, true)['items'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Spotify Favorites</title>
</head>
<body>
    <h1>Your Top Artists</h1>
    <ul>
        <?php foreach($artists as $artist): ?>
            <li><?php echo $artist['name']; ?></li>
        <?php endforeach; ?>
    </ul>

    <h1>Your Top Songs</h1>
    <ul>
        <?php foreach($tracks as $track): ?>
            <li><?php echo $track['name']; ?> by <?php echo $track['artists'][0]['name']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
