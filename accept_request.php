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

// Get the ID of the friend request being accepted
$request_id = $_GET['id'];

// Update the friend request status to 'accepted'
$conn->query("UPDATE FriendRequest SET status = 'accepted' WHERE id = $request_id");

// Fetch the sender and receiver IDs
$request_info = $conn->query("SELECT sender_id, receiver_id FROM FriendRequest WHERE id = $request_id")->fetch_assoc();
$sender_user_id = $request_info['sender_id'];
$receiver_user_id = $request_info['receiver_id'];

// Add the users to the friends table (you might need to adjust this based on your database structure)
$conn->query("INSERT INTO friends (user1_id, user2_id) VALUES ($sender_user_id, $receiver_user_id)");


// Redirect back to the main page
header("Location: homepage_page.html");
exit();
?>
