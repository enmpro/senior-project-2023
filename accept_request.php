<?php
require_once 'logindb.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#accepted friend request id
$request_id = $_GET['id'];

#changes status to "accepted
$conn->query("UPDATE FriendRequest SET Status = 'accepted' WHERE id = $request_id");

#fetches sender id
$request_info = $conn->query("SELECT sender_id FROM friend_requests WHERE id = $request_id")->fetch_assoc();
$sender_user_id = $request_info['sender_id'];

#adds user to friends table
$conn->query("INSERT INTO friends (user1_id, user2_id) VALUES ($sender_user_id, $receiver_user_id)");

#database connection is closed
$conn->close();

#redirects back to homepae
header("Location: homepage_page.html");
exit();
?>
