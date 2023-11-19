<?php

#connect to database
require_once 'logindb.php'; 
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#takes the username from the form (friend_request.html)
$username = $_POST['username'];

#inserts the friend request into the database
$sql = "INSERT INTO Users (Username) VALUES ('$username')";
$conn->query($sql);

#database connection closes
$conn->close();

#takes user back to the homepage
header("Location: homepage_page.html");
exit();
?>
