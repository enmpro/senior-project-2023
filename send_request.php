<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

session_start();
if (!isset($_SESSION['user_name'])) {
    #The user is not logged in, redirect them to the login page
    header('Location: landing.html');
    exit;
}

$AuthUserID = $_SESSION['UserID'];

function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $friend_username = $_POST['friend_username'];
    $SenderUserID = $AuthUserID;

    try {
        $stmt = $conn->prepare("SELECT UserID FROM User WHERE Username= :Username");
        $stmt->bindParam(':Username', $friend_username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $FriendUserID = $result['UserID'];

            $existingRequest = $conn->prepare("SELECT * FROM FriendRequest WHERE RequestSend = :RequestSend AND RequestReceive = :RequestReceive");
            $existingRequest->bindParam(':RequestSend', $SenderUserID);
            $existingRequest->bindParam(':RequestReceive', $FriendUserID);
            $existingRequest->execute();

            if ($existingRequest->rowCount() === 0) {
                $insertRequest = $conn->prepare("INSERT INTO FriendRequests (RequestSend, RequestReceive, Status) VALUES (:RequestSend, :RequestReceive, 'pending')");
                $insertRequest->bindParam(':RequestSend', $SenderUserID);
                $insertRequest->bindParam(':RequestReceive', $FriendUserID);
                $insertRequest->execute();

                echo "Friend request sent successfully!";
            } else {
                echo "Friend request already sent!";
            }
        } else {
            echo "Sorry, this user was not found in your system.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$pdo = null;

?>
