<?php
require_once 'logindb.php';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the username from the form
$username = $_POST['username'];

// Insert the friend request into the database
$sql = "INSERT INTO Users (Username) VALUES ('$username')";
$conn->query($sql);

// Close the database connection
$conn->close();

// Redirect back to the main page
header("Location: homepage_page.html");
exit();
?>
