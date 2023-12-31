<?php
require_once '../logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


session_start();
if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: ../landing.html');
    exit;
}

$username = $_SESSION['user_name'];
$userID = $_SESSION['user_id'];


$query = "SELECT * FROM EventOrganizer WHERE UserID LIKE $userID";
$result = $pdo->query($query);

if ($row = $result->fetch()) {
    $organizerBool = true;
} else {
    $organizerBool = false;
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
                    <form method="post" action="../user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>

    <?php

    if (!isset($_SESSION['accessToken'])) {

        ?>

        <div class="container mt-3">
            <h1 class="text-center">Sign into Spotify!</h1>
            <div class="text-center mt-5 mb-5">
                <a class="btn btn-primary fs-1" href="auth.php">Spotify Sign In</a>
            </div>
        </div>

        <?php

    } else {

        ?>

        <div class="container mt-3">
            <h1 class="text-center">Explore the music!</h1>

            <div class="text-center mt-5 mb-5">
                <a class="btn btn-primary fs-1" href="search_artist.php">Search for an artist</a>
            </div>
            <div class="text-center mt-5 mb-5">
                <a class="btn btn-primary fs-1" href="search_album.php">Search for an album</a>
            </div>
            <div class="container text-center">
                <div class="row row-cols-3">
                    <div class="col">
                        <a class="btn btn-primary fs-2" href="new_releases.php">New Releases</a>
                    </div>
                    <div class="col">
                        <a class="btn btn-primary fs-2" href="top_us_songs.php">Top US Songs</a>
                    </div>
                    <div class="col">
                        <a class="btn btn-primary fs-2" href="app.php">Spotify Profile</a>
                    </div>
                </div>
            </div>

        </div>

        <?php

    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>