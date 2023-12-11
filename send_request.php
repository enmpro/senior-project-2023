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

$AuthUserID = $_SESSION['UserID'];

function test_userinput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_POST['add_friend'])) {
  $RequestSend = $AuthUserID;
  $friend_username = test_userinput($_POST['RequestReceive']);

  try {
    $stmt = $pdo->prepare("SELECT UserID FROM User WHERE Username = :Username");
    $stmt->bindParam(':Username', $friend_username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      $FriendUserID = $result['UserID'];

      // Check if a friend request has already been sent to this user
      $existingRequest = $pdo->prepare("SELECT * FROM FriendRequest WHERE RequestSend = :RequestSend AND RequestReceive = :RequestReceive");
      $existingRequest->bindParam(':RequestSend', $RequestSend);
      $existingRequest->bindParam(':RequestReceive', $FriendUserID);
      $existingRequest->execute();

      if ($existingRequest->rowCount() > 0) {
        $message = "Friend request has already been sent!";
      } else {
        // Insert a new friend request into the database
        $insertRequest = $pdo->prepare("INSERT INTO FriendRequest (RequestSend, RequestReceive, Status) VALUES (:RequestSend, :RequestReceive, 'pending')");
        $insertRequest->bindParam(':RequestSend', $RequestSend);
        $insertRequest->bindParam(':RequestReceive', $FriendUserID);
        $insertRequest->execute();

        $message = "Friend request has successfully been sent!";
      }
    } else {
      $message = "Sorry, we could not send a friend request at this time. Please try again later.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

$pdo = null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Send Request</title>
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
</body>

</html>
