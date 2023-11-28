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

$userID = $_SESSION['user_id'];

function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

#takes the username from the form (friend_request.html)
$friend_username = $_POST['friend_username'];

$sender_user_id = 1;

$result = $sql("SELECT Username FROM User WHERE Username = '$friend_username'");

if ($result->num_rows > 0) {
    #friend is found, get their user ID
    $row = $result->fetch_assoc();
    $friend_user_id = $row['Username'];

    #checks if a friend request already exists
    $existingRequest = sql("SELECT * FROM FriendRequest WHERE RequestSend = $sender_user_id AND RequestReceive = $friend_user_id");

    if ($existingRequest->num_rows === 0) {
        #if there is no existing request, send a new friend request
        sql("INSERT INTO FriendRequest (RequestSend, RequestReceive, status) VALUES ($sender_user_id, $friend_user_id, 'pending')");

        echo "Friend request sent successfully!";
    } else {
        echo "Friend request already sent!"; #lets user know that a request has already been sent
    }
} else {
    #friend is not found
    echo "Friend not found in the database!";
}
?>
