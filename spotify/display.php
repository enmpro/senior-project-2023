<?php

require_once 'logindb.php';


session_start();
if (!isset($_SESSION['user_id'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: main.php');
    exit;
}

$access_token = $_SESSION['access_token'];
$username = $_SESSION['user_id'];

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
    //<?php add_artist($pdo, $artist['name'])
    //<?php add_song($pdo, $track['artists'][0]['name']);


}

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
<?php


    echo "Test";
    function add_song($pdo, $song_name){
        $sql = "INSERT INTO Song(SongName) VALUES(:songname)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParm(':songname', $song_name, PDO::PARAM_STR, 50);

        $stmt->execute();

    }

    function add_artist($pdo, $artist_name){
        $sql = "INSERT INTO Artist(ArtistName) VALUES(:artistname)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam (':artistname', $artist_name, PDO::PARAM_STR, 50);
        
        $stmt->execute();

    }

?>