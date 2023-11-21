<?php
require_once 'logindb.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the ID of the friend request being rejected
$request_id = $_GET['id'];

// Update the friend request status to 'rejected'
$conn->query("UPDATE friend_requests SET status = 'rejected' WHERE id = $request_id");

// Close the database connection
$conn->close();

// Redirect back to the main page
header("Location: homepage_page.html");
exit();
?>
