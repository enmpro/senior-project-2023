<?php
require_once 'logindb.php';

// Get the ID of the friend request being accepted
$request_id = $_GET['id'];

// Update the friend request status to 'accepted'
$conn->query("UPDATE friend_requests SET status = 'accepted' WHERE id = $request_id");

// Fetch the sender and receiver IDs
$request_info = $conn->query("SELECT sender_id, receiver_id FROM friend_requests WHERE id = $request_id")->fetch_assoc();
$sender_user_id = $request_info['sender_id'];
$receiver_user_id = $request_info['receiver_id'];

// Add the users to the friends table (you might need to adjust this based on your database structure)
$conn->query("INSERT INTO friends (user1_id, user2_id) VALUES ($sender_user_id, $receiver_user_id)");

// Close the database connection
$conn->close();

// Redirect back to the main page
header("Location: homepage_page.html");
exit();
?>
