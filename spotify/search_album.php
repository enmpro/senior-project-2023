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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CANTIO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="explore_page.php">Explore Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../community.php">Community</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../user_event.php">Event</a>
                    </li>
                    <?php
                    if ($organizerBool) {
                        echo <<<_END
                    <li class="nav-item">
                        <a class="nav-link" href="event_coord.php">Event Coordinator</a>
                    </li>
                    _END;
                    }
                    ?>

                </ul>
                <div>
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>

    <div class="container mt-3 mb-3">
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

            ?>
            <div class="container">
                <?php
                foreach ($searcher->artists->items as $artist) {

                    ?>


                    <div class="card mb-3" style="width: 300px;">
                        <img src="<?php echo $artist->images[0]->url ?>" alt="" srcset="" style="height: 150px; width: 150px;">
                        <p> Name: <?php echo $artist->name ?></p>
                        <p> Popularity: <?php echo $artist->popularity ?></p>
                    </div>



                    <?php
                }

                ?>
                <div>

                    <?php
        }
    }
    ?>

            <!-- Latest compiled JavaScript -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>