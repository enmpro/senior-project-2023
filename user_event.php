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

if (isset($_SESSION['user_id'])) {

    $userID = $_SESSION['user_id'];
}


$username = $_SESSION['user_name'];

$stmt = $pdo->query('SELECT * FROM Event');
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$eventCheck = "SELECT * FROM EventOrganizer WHERE UserID LIKE $userID";
$resultCheck = $pdo->query($eventCheck);

if ($rowCheck = $resultCheck->fetch()) {
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
    <title>Events</title>
    <script src="https://kit.fontawesome.com/fe58b05d68.js" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user_event.css">
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
                        <a class="nav-link" href="search_users.php">Search Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="user_event.php">Event</a>
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

    <div class="container mt-5">
        <h2 class="mb-4">Upcoming Music Events</h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?php
            foreach ($events as $index => $event) {
                $modalId = 'eventModal' . $index;
                ?>

                <div class="col mb-4">
                    <div class="card">
                        <img src="<?php echo $event['EventPhoto']; ?>" class="card-img-top card-img" alt="Event Picture">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $event['EventName']; ?></h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#<?php echo $modalId; ?>">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Modals -->
        <?php
        foreach ($events as $index => $event) {
            $modalId = 'eventModal' . $index;
            ?>
            <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1"
                aria-labelledby="<?php echo $modalId; ?>Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="<?php echo $modalId; ?>Label"><?php echo $event['EventName']; ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="<?php echo $event['EventPhoto']; ?>" class="modal-img" alt="Concert in the Park">
                            <p class="card-text row g-2">
                                <span class="col-auto" style="font-size: 16px;"><i class='fas fa-calendar-alt'></i></span>
                                <span class="col">Date: <?php echo $event['EventDateTime']; ?></span>
                            </p>
                            <p class="card-text row g-2">
                                <span class="col-auto" style="font-size: 14px;"><i class='fas fa-location'></i></span>
                                <span class="col">Location: Arena Stadium, Los Angeles (Placeholder text)</span>
                            </p>
                            <p class="card-text row g-2">
                                <span class="col-auto" style="font-size: 14px;"><i class='fas fa-music'></i></span>
                                <span class="col">Artist: <?php echo $event['EventArtist']; ?></span>
                            </p>
                            <p class="card-text">
                            <div class="d-flex ">
                                <div class="pe-1">
                                    <p>
                                        Description:
                                    </p>
                                </div>
                                <div>
                                    <p>
                                        <?php echo $event['EventDesc']; ?>
                                    </p>
                                </div>
                            </div>
                            </p>
                        </div>

                        <?php
                        $checkUserRSVP = "SELECT * From UserRSVP WHERE UserID = :userID AND EventID = :eventID";
                        $resultUserRSVP = $pdo->prepare($checkUserRSVP);
                        $resultUserRSVP->bindParam(':userID', $userID, PDO::PARAM_INT);
                        $resultUserRSVP->bindParam(':eventID', $event['EventID'], PDO::PARAM_INT);
                        $resultUserRSVP->execute();

                        if ($resultUserRSVP->rowCount() > 0) {


                            ?>
                            <div class="modal-footer">
                                <form class="text-center" action="user_rsvp.php" method="post">
                                    <button type="submit" class="btn btn-primary" disabled>Already RSVP'd / Interested</button>
                                </form>
                            </div>

                            <?php


                        } else {


                            ?>

                            <div class="modal-footer">
                                <form class="text-center" action="user_rsvp.php" method="post">
                                    <input type="hidden" name="event_id" value="<?php echo $event['EventID']; ?>">
                                    <label class="form-label" for="rsvp_status">RSVP Status</label>
                                    <select class="form-select mb-3" name="rsvp_status" id="rsvp_status" required>
                                        <option value="Attending">Attending</option>
                                        <option value="Maybe">Interested</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">RSVP</button>
                                </form>
                            </div>

                            <?php

                        }
                        ?>


                    </div>
                </div>
            </div>
        <?php } ?>

    </div>




    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>