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
    <title>Community</title>
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
                        <a class="nav-link" aria-current="page" href="homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spotify/explore_page.php">Explore Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="community.php">Community</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_event.php">Event</a>
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
                <div class="my-3 mx-4">
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 bg-light">
                <h2>Sidebar</h2>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="search_users.php">Search Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="display_requests.php">Friend Requests</a></li>
                </ul>
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <h1>Community Page</h1>
                <!-- Add your main content here -->
            </div>
        </div>
    </div>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>