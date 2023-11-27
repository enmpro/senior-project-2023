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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Event Coordinator</title>
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
                        <a class="nav-link active" aria-current="page" href="homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="community.php">Community</a>
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
                <div>
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>
    <div class="d-flex justify-content-center">
        <div class="p-3 card mt-3 container-sm">
            <h2 class="text-center">Create an event</h2>
            <p>
                As an event coordinator, you have the power to create
                events for people to view and show that they have
                interest with those attending and events that appear
                on their profiles.
            </p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#eventCreateModal">Create
                event</button>
            <div class="modal fade" id="eventCreateModal" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="eventHeading" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eventHeading">New Event!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="create_event.php" method="post" enctype="multipart/form-data">
                                <div class="m-3">
                                    <label for="eventName">Event Name</label>
                                    <input id="eventName" name="eventName" type="text" required />
                                </div>
                                <div class="m-3">
                                    <label for="eventArtist">Event Artist</label>
                                    <input id="eventArtist" name="eventArtist" type="text" required />
                                </div>
                                <div class="m-3">
                                    <label for="eventDesc">Event Description</label>
                                    <textarea id="eventDesc" name="eventDesc" placeholder="Insert Description Here..."
                                        required></textarea>
                                </div>
                                <div class="m-3">
                                    <label for="eventPhoto">Event Photo</label>
                                    <input id="eventPhoto" name="eventPhoto" type="file" required />
                                </div>
                                <div class="m-3">
                                    <label for="eventDate">Event Date and Time</label>
                                    <input id="eventDate" name="eventDate" type="datetime-local" required />
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
    </div>
    <div class="container mt-3">
        <div class="p-3 card">
            <h2>Active events</h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Event Artist</th>
                        <th scope="col">Description</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Date and Time</th>
                        <th scope="col">People Attending</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $query = "SELECT * FROM EventOrganizer WHERE UserID LIKE $userID";
                    $result = $pdo->query($query);

                    if ($row = $result->fetch()) {
                        $organizerID = $row["OrganizerID"];
                    }

                    $query2 = "SELECT * FROM Event WHERE OrganizerID LIKE $organizerID";
                    $result2 = $pdo->query($query2);
                    $count = 0;
                    foreach ($result2 as $row) {
                        $count = $count + 1;
                        $event_id = $row["EventID"];
                        $event_Name = $row["EventName"];
                        $event_Artist = $row["EventArtist"];
                        $event_Desc = $row["EventDesc"];
                        $event_DateTime = $row["EventDateTime"];
                        $event_Photo = $row["EventPhoto"];
                        $userNumAttend = $row["UserNumAttend"];
                        echo <<<_END
                        
                            <tr>
                                <th scope="row">$count</th>
                                <td>$event_Name</td>
                                <td>$event_Artist</td>
                                <td>$event_Desc</td>
                                <td> <img src="$event_Photo" style="width: 250px" alt="Profile Image" class="profile-image"></td>
                                <td>$event_DateTime</td>
                                <td>$userNumAttend</td>
                                <td> 
                                    <div>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#eventEdit">Edit</button>
                                    </div>
                                    <div class="modal fade" id="eventEdit" data-bs-backdrop="static" data-bs-keyboard="false"
                                        tabindex="-1" aria-labelledby="eventEdit" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5>Edit Event</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="edit_event.php" method="post" enctype="multipart/form-data">
                                                        <div class="m-3">
                                                            <label for="eventName">Event Name</label>
                                                            <input id="eventName" name="eventName" type="text" required />
                                                        </div>
                                                        <div class="m-3">
                                                            <label for="eventArtist">Event Artist</label>
                                                            <input id="eventArtist" name="eventArtist" type="text" required />
                                                        </div>
                                                        <div class="m-3">
                                                            <label for="eventDesc">Event Description</label>
                                                            <textarea id="eventDesc" name="eventDesc" placeholder="Insert Description Here..."
                                                                required></textarea>
                                                        </div>
                                                        <div class="m-3">
                                                            <label for="eventPhoto">Event Photo</label>
                                                            <input id="eventPhoto" name="eventPhoto" type="file" required />
                                                        </div>
                                                        <div class="m-3">
                                                            <label for="eventDate">Event Date and Time</label>
                                                            <input id="eventDate" name="eventDate" type="datetime-local" required />
                                                        </div>
                                                        <div class="text-center">
                                                            <input type="hidden" name="id" value="$event_id">
                                                            <button type="submit" name="submit">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    |
                                    <form action="delete_event.php" method="post">
                                        <input type="hidden" name="id" value="$event_id">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        _END;

                    }

                    ?>

            </table>
        </div>
    </div>
    </div>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>