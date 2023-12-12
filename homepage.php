<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


session_start();
if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: landing.html');
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Homepage</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<style>
    /* Add custom styles here, if needed */
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
                        <a class="nav-link active" aria-current="page" href="homepage.php">Main</a>
                    </li>
                <div class="my-3 mx-4">
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>
    <div class="container mt-3">
        <div class="row g-5 row-cols-1 row-cols-md-2 ">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">User Profile</h5>
                        <p class="card-text">View and manage your user profile information.</p>
                        <a href="profile.php" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>

            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Community Area</h5>
                        <p class="card-text">Connect with other music lovers in our community.

                        </p>
                        <a href="community.php" class="btn btn-primary">Go to Community</a>
                    </div>
                </div>

            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Music Area</h5>
                        <p class="card-text">Connect your Spotify account for a personalized experience.</p>
                        <a href="/spotify/explore_page.php" class="btn btn-primary">Go to Explore Music</a>
                    </div>
                </div>

            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Events Area</h5>
                        <p class="card-text">Explore and join upcoming music events in your area.</p>
                        <a href="user_event.php" class="btn btn-primary">Go to Events</a>
                    </div>
                </div>

            </div>
            <?php
            if ($organizerBool) {

                ?>

                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Events Coordinator Area</h5>
                            <p class="card-text">Create new events for users to attend and meet new people.</p>
                            <a href="user_event.php" class="btn btn-primary">Go to Event Coordinator</a>
                        </div>
                    </div>

                </div>
                <?php

            }
            ?>

        </div>
    </div>

    <footer class="bg-light text-center py-4 fixed-bottom">
        <p>&copy; 2023 Cantio. All rights reserved.</p>
    </footer>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>