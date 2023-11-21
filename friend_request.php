<?php

#connect to database
require_once 'logindb.php'; 
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#takes the username from the form (friend_request.html)
$friend_username = $_POST['friend_username'];

$sender_user_id = 1;

$result = $conn->query("SELECT Username FROM Users WHERE Username = '$friend_username'");

if ($result->num_rows > 0) {
    // Friend found, get their user ID
    $row = $result->fetch_assoc();
    $friend_user_id = $row['id'];

    // Check if a friend request already exists
    $existingRequest = $conn->query("SELECT * FROM friend_requests WHERE sender_id = $sender_user_id AND receiver_id = $friend_user_id");

    if ($existingRequest->num_rows === 0) {
        // No existing request, send a new friend request
        $conn->query("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES ($sender_user_id, $friend_user_id, 'pending')");

        echo "Friend request sent successfully!";
    } else {
        echo "Friend request already sent!";
    }
} else {
    // Friend not found
    echo "Friend not found in the database!";
}
#database connection closes
$conn->close();
?>
