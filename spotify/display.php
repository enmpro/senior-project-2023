<?php
require_once '../logindb.php';

// TODO: establish a connection to our database
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


// SongID	UserID	SongName

session_start();
$access_token = $_SESSION['access_token'];
//echo $access_token;


// TODO: fetch the users id from the session or our db 
$user_id = $_SESSION['user_id'];
//echo $user_id;

// Fetch Top Artists
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/top/artists?limit=10');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$access_token}"));
$response = curl_exec($ch);
$artists = json_decode($response, true)['items'];

// Fetch Top Tracks
curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/top/tracks?limit=10');
$response = curl_exec($ch);
$tracks = json_decode($response, true)['items'];
?>

<?php
function add_song($pdo, $user_id, $song_name){
    $sql = "INSERT INTO Song (UserID, SongName) VALUES(:userid, :songname)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userid', $user_id, PDO::PARAM_INT, 11);
    $stmt->bindParam(':songname', $song_name, PDO::PARAM_STR, 255);

    $stmt->execute();

}
?>
<?php
function checkDuplicateSong($pdo, $songName, $artistName) {
    $sql = "SELECT COUNT(*) FROM Song WHERE SongName = :songname AND ArtistName = :artistname";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':songname', $songName);
    $stmt->bindParam(':artistname', $artistName);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    return $count > 0; // returns true if a duplicate exists
}
?>

<?php
function add_artist($pdo, $user_id, $artist_name){
    $sql = "INSERT INTO Artist (UserID, ArtistName) VALUES(:userid, :artistname)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userid', $user_id, PDO::PARAM_INT, 11);
    $stmt->bindParam(':artistname', $artist_name, PDO::PARAM_STR, 255);
    
    $stmt->execute();

}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Spotify Favorites</title>
</head>
<body>
    <h1>Your Top Artists</h1>
    <ul>
        <!-- Display the top SongID	UserID	SongName	-->
        <?php foreach($artists as $artist): ?>
            <li><?php echo $artist['name']; ?></li>
            <?php $user_id = $_SESSION['user_id'];?>
            <?php add_artist($pdo, $user_id, $artist['name']); ?>


        <?php endforeach; ?>
    </ul>

    <h1>Your Top Songs</h1>
    <ul>
        <?php foreach($tracks as $track): ?>
            <li><?php echo $track['name']; ?> by <?php echo $track['artists'][0]['name']; ?></li>
            <?php add_song($pdo, $user_id, $track['name']); ?>
        <?php endforeach; ?>
    </ul>
</body>
</html>

