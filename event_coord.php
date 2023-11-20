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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Event Coordinator</title>
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
                        <a class="nav-link" href="event_coord.html">Event Coordinator</a>
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
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-md-auto p-3 card">
                <h2>Create an event</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#eventCreateModal">Create event</button>
                <div class="modal fade" id="eventCreateModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="eventHeading" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventHeading">New Event!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="create_event.php" method="post" enctype="multipart/form-data">
                                    <div class="m-3">
                                        <label for="eventName">Event Name</label>
                                        <input id="eventName" name="eventName" type="text" required />
                                    </div>
                                    <div class="m-3">
                                        <label for="eventDesc">Event Description</label>
                                        <textarea id="eventDesc" name="eventDesc"
                                            placeholder="Insert Description Here..." required></textarea>
                                    </div>
                                    <div class="m-3">
                                        <label for="eventPhoto">Event Photo</label>
                                        <input id="eventPhoto" name="eventPhoto" type="file" required />
                                    </div>
                                    <div class="text-center">
                                        <input class="my-3 btn btn-primary" name="submit" type="submit"
                                            value="Create Event">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-auto p-3 card">
                <h2>Active events</h2>
                <?php
                $query = "SELECT * FROM EventOrganizer WHERE UserID LIKE $userID";
                $result = $pdo->query($query);

                if ($row = $result->fetch()) {
                    $organizerID = $row["OrganizerID"];
                }

                $query2 = "SELECT * FROM Event WHERE OrganizerID LIKE $organizerID";
                $result2 = $pdo->query($query2);
                
                foreach ($result2 as $row) {
                    // $event_Name = $row["EventName"];
                    // $event_Desc = $row["eventDesc"];
                    // $event_Photo = $row["EventPhoto"];
                    // $userNumAttend = $row["UserNumAttend"];
                    echo "Event: ". $row["EventName"] ." ". $row["EventDesc"] ." ". $row["EventPhoto"] ." ". $row["UserNumAttend"];
                }
                
                ?>
            </div>
        </div>
    </div>
</body>

</html>