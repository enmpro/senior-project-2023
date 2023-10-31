<?php
require_once 'login.php';

$host = 'localhost:3306';
$data = 'gummybea_webapp';
$user = 'gummybea_user1';        
$pass = 'V{V6zYAam}54';

session_start();

$RequestSend = $_SESSION['UserID']; #gets the id of the user that sends the request
$RequestReceive = $_POST['RequestReceive']; #gets the id of the user that receives the request

#inserts the friend request into the database
$sql = "INSERT INTO FriendRequest (RequestSend, RequestSend) VALUES ($RequestSend, $RequestReceived)";
$conn->query($sql);

#accepting or rejecting friend requests
$RequestID = $_POST['$RequestID']; //gets the if of the friend request
$action = $_POST['action']; //gets the action (accept or reject)

#Update the status of the friend request
$sql = "UPDATE FriendRequest SET status = '$action' WHERE id = $$RequestID";
$conn->query($sql);
?>