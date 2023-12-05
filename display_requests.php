<?php
require_once 'logindb.php';

function connectToDatabase($attr, $user, $pass, $opts) {
    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int) $e->getCode());
    }
}

function getPendingFriendRequests($pdo, $currentUserID) {
    $query = "SELECT UserID, RequestSend FROM FriendRequest 
              WHERE RequestReceive = :RequestReceive AND Status = 'pending'";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':RequestReceive', $currentUserID);
    $stmt->execute();
    return $stmt;
}

session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: landing.html');
    exit;
}

$CurrentUserID = $_SESSION['UserID'];

try {
    $pdo = connectToDatabase($attr, $user, $pass, $opts);
    $result = getPendingFriendRequests($pdo, $CurrentUserID);

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $SenderUserID = $row['RequestSend'];
        echo "<li>{$SenderUserID} wants to be your friend! 
        <form action='accept_request.php' method='get' style='display:inline;'>
          <input type='hidden' name='UserID' value='{$SenderUserID}'>
          <button type='submit'>Accept</button>
        </form>
        <form action='reject_request.php' method='get' style='display:inline;'>
          <input type='hidden' name='UserID' value='{$SenderUserID}'>
          <button type='submit'>Reject</button>
        </form>
        </li>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $pdo = null;
}
?>
