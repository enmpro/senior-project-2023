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
    header('Location: ../landing.html');
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
<style>
    body {
        padding-top: 100px;
        /* Adjust for fixed navbar height */
    }

    .homepage-section {
        padding: 60px 0;
    }
</style>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">CANTIO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="../profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="explore_page.php">Explore Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../search_users.php">Search Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../user_event.php">Event</a>
                    </li>
                    <?php
                    if ($organizerBool) {
                        echo <<<_END
                    <li class="nav-item">
                        <a class="nav-link" href="../event_coord.php">Event Coordinator</a>
                    </li>
                    _END;
                    }
                    ?>

                </ul>
                <div class="my-3 mx-4">
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>


    <?php
    // Check if the form is submitted
    $releases = $api->getNewReleases([
        'country' => 'us',
    ]);

    ?>
    <div class="container mt-3">
        <div class="row row-cols-3 row-cols-md-3">
            <?php
            // foreach ($releases->albums->items as $album) {
//     echo '<a href="' . $album->external_urls->spotify . '">' . $album->name . '</a> <br>';
//     echo $album->images[0]->url;
// }
            foreach ($releases->albums->items as $album) {

                ?>


                <div class="col card mb-3 me-3 text-center" style="width: 300px;">
                    <img class="card-img-top card-img m-auto" src="<?php echo $album->images[0]->url ?>" alt="" srcset=""
                        style="height: 150px; width: 150px;">
                    <div class="card-body">
                        <p> Album Name:
                            <?php echo $album->name ?>
                        </p>
                        <p> Number of Tracks:
                            <?php echo $album->total_tracks ?>
                        </p>
                        <p> Release Date:
                            <?php echo $album->release_date ?>
                        </p>
                        <p> Artist(s):
                            <?php echo $album->artists[0]->name ?>
                        </p>
                    </div>
                </div>
                <?php
            }

            ?>
        </div>
    </div>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>