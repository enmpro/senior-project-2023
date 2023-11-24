<?php
require_once 'login.php';

session_start();
if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: landing.html');
    exit;
}

if (isset($_SESSION['user_id'])) {

    $userID = $_SESSION['user_id'];
}


$username = $_SESSION['user_name'];




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="community.php">Community</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_event.html">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="event_coord.php">Event Coordinator</a>
                    </li>
                    <div>
                        <form method="post" action="user_logout.php">
                            <button type="submit" name="logout">Log Out</button>
                        </form>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <header>
        <div class="d-flex justify-content-around mt-4 mb-4">
            <button class="btn btn-primary fs-4">
                Current Events
            </button>
            <button class="btn btn-primary fs-4">
                Past Events
            </button>
        </div>

    </header>

    <div class="list-group container-sm ">

        <?php
        $query = "SELECT * FROM Event";
        $result = $pdo->query($query);
        $count = 0;
        foreach ($result as $row) {
            $count = $count + 1;
            $event_id = $row["EventID"];
            $event_Artist = $row["EventArtist"];
            $event_Name = $row["EventName"];
            $event_Desc = $row["EventDesc"];
            $event_DateTime = $row["EventDateTime"];
            $event_Photo = $row["EventPhoto"];
            $userNumAttend = $row["UserNumAttend"];

            echo <<<_END
                <a href="" class="list-group-item list-group-item-action" aria-current="true" data-bs-toggle="modal"
                    data-bs-target="#eventRsvp$count">
                    <div class="d-flex w-100 justify-content-around mt-3">
                        <h5 class="mb-1">$event_Name</h5>
        
                        <small>$event_DateTime</small>
                    </div>
                    <p class="mt-2 mb-3 text-center">Artist</p>
                </a>
                <div class="modal fade" id="eventRsvp$count" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="eventHeading" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventHeading">RSVP to this Event</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-evenly">
                                    <img src="$event_Photo" width="250px" alt="event picture">
                                    <div>
                                        <h3>
                                            <span><i class='fas fa-music'></i></span> $event_Artist
                                        </h3>
                                        <h3>
                                            <span><i class='far fa-calendar-alt'></i></span> $event_DateTime
                                        </h4>
                                        <p>
                                            $event_Desc
                                        </p>
                                    </div>
                                </div>
                                <form class="text-center mt-3" action="" method="post">
                                    <button class="btn btn-primary" type="submit" name="RSVP" value="RSVP">RSVP</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
        _END;

        }

        ?>


    </div>

</body>

</html>