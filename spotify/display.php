<?php
//require_once '/logindb.php';

echo "test1";


// try {
//     echo "test";
//     $pdo = new PDO($attr, $user, $pass, $opts);
// } catch (PDOException $e) {
//     throw new PDOException($e->getMessage(), (int) $e->getCode());
// }

// TODO: fetch the users id from the session or our db
// TODO: establish a connection to our database
// TODO: Send songs to the database
// SongID	UserID	SongName

session_start();
$access_token = $_SESSION['access_token'];
echo $access_token;

$temp = $_SESSION['user_id'];
echo $temp;

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

<?php
function add_song($pdo, $user_id, $song_name){
    $sql = "INSERT INTO Song (UserID, SongName) VALUE(:userid, :songname)";

    $stmt = $pdo->prepare($sql);

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