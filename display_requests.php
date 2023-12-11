<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: landing.html');
    exit;
}

function getPendingFriendRequests($pdo, $currentUserID) {
    $query = "SELECT UserID, RequestSend FROM FriendRequest 
              WHERE RequestReceive = :RequestReceive AND Status = 'pending'";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':RequestReceive', $currentUserID);
    $stmt->execute();
    return $stmt;
}

$CurrentUserID = $_SESSION['UserID'];

try {
    $result = getPendingFriendRequests($pdo, $CurrentUserID);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $SenderUserID = $row['RequestSend'];
        ?>
        <li>
            <?php echo "{$SenderUserID} wants to be your friend!"; ?>
            <form action='accept_request.php' method='post' style='display:inline;'>
                <input type='hidden' name='UserID' value='<?php echo $SenderUserID; ?>'>
                <button type='submit'>Accept</button>
            </form>
            <form action='reject_request.php' method='post' style='display:inline;'>
                <input type='hidden' name='UserID' value='<?php echo $SenderUserID; ?>'>
                <button type='submit'>Reject</button>
            </form>
        </li>
        <?php
    }
} 
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} 
finally {
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Friend Requests</title>
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
                    <div>
                        <form method="post" action="user_logout.php">
                            <button type="submit" name="logout">Log Out</button>
                        </form>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
