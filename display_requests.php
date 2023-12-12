<?php
require_once 'logindb.php';

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: landing.html');
    exit;
}

// Function to retrieve pending friend requests
function getPendingFriendRequests($pdo, $currentUserID) {
    $query = "SELECT UserID, RequestSend FROM FriendRequest 
              WHERE RequestReceive = :RequestReceive AND Status = 'pending'";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':RequestReceive', $currentUserID);
    $stmt->execute();
    return $stmt;
}

$currentUserID = $_SESSION['UserID'];

try {
    // Initialize PDO connection
    $pdo = new PDO($attr, $user, $pass, $opts);
    
    // Retrieve pending friend requests
    $result = getPendingFriendRequests($pdo, $currentUserID);
} catch (PDOException $e) {
    // Handle database error
    echo "Error: " . $e->getMessage();
} finally {
    $pdo = null; // Close the PDO connection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Pending Friend Requests</title>
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
                        <a class="nav-link" href="/spotify/explore_page.php">Explore Music</a>
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

    <div class="container mt-5">
        <h2>Pending Friend Requests</h2>
        <ul>
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                <li>
                    <?php echo "{$row['RequestSend']} wants to be your friend!"; ?>
                    <form action='accept_request.php' method='post' style='display:inline;'>
                        <input type='hidden' name='UserID' value='<?php echo $row['RequestSend']; ?>'>
                        <button type='submit'>Accept</button>
                    </form>
                    <form action='reject_request.php' method='post' style='display:inline;'>
                        <input type='hidden' name='UserID' value='<?php echo $row['RequestSend']; ?>'>
                        <button type='submit'>Reject</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
