<?php
session_start();
require '../vendor/autoload.php';

require_once '../logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: landing.html');
    exit;
}

$userID = $_SESSION['user_id'];

$api = new SpotifyWebAPI\SpotifyWebAPI();

$accessToken = $_SESSION['accessToken'];

// Fetch the saved access token from somewhere. A session for example.
$api->setAccessToken($accessToken);


// $releases = $api->getNewReleases([
//     'country' => 'us',
// ]);

// foreach ($releases->albums->items as $album) {
//     echo '<a href="' . $album->external_urls->spotify . '">' . $album->name . '</a> <br>';
//     echo $album->images[0]->url;
// }

// $searcher = $api->search('Taylor', 'artist');

// foreach ($searcher->artists->items as $artist) {
//     echo $artist->name;
// }


function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <div class="container">
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="container input-group input-group-lg">
                <span class="input-group-text" id="inputGroup-sizing-lg">Artist Search</span>
                <input type="text" class="form-control" name="search" id="search" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-lg" <?php
                if (isset($_GET['search'])) {
                    $search = test_userinput($_GET["search"]);
                    echo "value=" . "'" . $search . "'";
                }
                ?>>
            </div>
        </form>
    </div>

    <?php
            // Check if the form is submitted
            if (isset($_GET['search'])) {
                $search = test_userinput($_GET["search"]);

                if ($search == '') {
                    echo "<p>No results found.</p>";

                } else {
                    $search = test_userinput($_GET["search"]);

                    $searcher = $api->search($search, 'artist');

                    foreach ($searcher->artists->items as $artist) {
                        echo $artist->name . '<br>';
                    }

                }
            }
            ?>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>