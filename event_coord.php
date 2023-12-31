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

<style>
    .event-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        overflow: hidden;
    }

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
                        <a class="nav-link " href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spotify/explore_page.php">Explore Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search_users.php">Search Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_event.php">Event</a>
                    </li>
                    <?php
                    if ($organizerBool) {
                        echo <<<_END
                    <li class="nav-item">
                        <a class="nav-link active" href="event_coord.php">Event Coordinator</a>
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
                                <div class="mb-3">
                                    <label for="eventName" class="form-label">Event Name</label>
                                    <input name="eventName" type="text" class="form-control" id="eventName"
                                        placeholder="Enter event name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="eventArtist" class="form-label">Event Artist</label>
                                    <input name="eventArtist" type="text" class="form-control" id="eventArtist"
                                        placeholder="Enter event name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="eventPhoto">Event Photo</label>
                                    <input class="form-control" id="eventPhoto" name="eventPhoto" type="file"
                                        required />
                                </div>

                                <div class="mb-3">
                                    <label for="eventDate" class="form-label">Event Date and Time</label>
                                    <input class="form-control" id="eventDate" name="eventDate" type="datetime-local"
                                        required />
                                </div>

                                <div class="mb-3">
                                    <label for="eventDesc" class="form-label">Event Description</label>
                                    <textarea class="form-control" name="eventDesc" id="eventDesc" rows="4"
                                        placeholder="Enter event description" required></textarea>
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
                                <td> <img class="event-photo" src="$event_Photo" style="width: 250px" alt="Profile Image" class="profile-image"></td>
                                <td>$event_DateTime</td>
                                <td>$userNumAttend</td>
                                <td> 
                                <div class="row row-cols-2">
                                    <div class="col">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#eventEdit$count">Edit</button>
                                    </div>
                                    <div class="col">
                                    <form action="delete_event.php" method="post">
                                        <input type="hidden" name="id" value="$event_id">
                                        <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                    </form>
                                    </div>
                                </div>
                                   
                                    <div class="modal fade" id="eventEdit$count" data-bs-backdrop="static" data-bs-keyboard="false"
                                        tabindex="-1" aria-labelledby="eventEdit$count" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5>Edit Event</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="edit_event.php" method="post" enctype="multipart/form-data">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="eventName">Event Name</label>
                                                            <input class="form-control"  id="eventName" name="eventName" type="text" value="$event_Name" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"  for="eventArtist">Event Artist</label>
                                                            <input class="form-control" id="eventArtist" name="eventArtist" type="text" value="$event_Artist" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"  for="eventDesc">Event Description</label>
                                                            <textarea  class="form-control" id="eventDesc" name="eventDesc" placeholder="Insert Description Here..."
                                                            >$event_Desc</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"  for="eventPhoto">Event Photo</label>
                                                            <input class="form-control" id="eventPhoto" name="eventPhoto" type="file" />
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"  for="eventDate">Event Date and Time</label>
                                                            <input class="form-control" id="eventDate" name="eventDate" type="datetime-local" value="$event_DateTime" />
                                                        </div>
                                                        <div class="text-center">
                                                            <input class="form-label"  type="hidden" name="id" value="$event_id">
                                                            <button type="submit" name="submit">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                   
                                </td>
                            </tr>
                        _END;

                    }

                    ?>

            </table>
        </div>
    </div>
    </div>
    <footer class="bg-light text-center py-4">
        <p>&copy; 2023 Cantio. All rights reserved.</p>
    </footer>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>