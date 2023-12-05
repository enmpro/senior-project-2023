<?php
require_once 'logindb.php';

try {
  $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
  throw
 
new PDOException($e->getMessage(), (int) $e->getCode());
}

session_start();
if (!isset($_SESSION['user_name'])) {
  // The user is not logged in, redirect them to the login page
  header('Location: landing.html');
  exit;
}

$AuthUserID = $_SESSION['UserID'];

function test_userinput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_POST['add_friend'])) {
  $RequestSend = $AuthUserID;
  $friend_username = test_userinput($_POST['RequestReceive']);

  try {
    $stmt = $pdo->prepare("SELECT UserID FROM User WHERE Username = :Username");
    $stmt->bindParam(':Username', $friend_username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      $FriendUserID = $result['UserID'];

      // Check if a friend request has already been sent to this user
      $existingRequest = $pdo->prepare("SELECT * FROM FriendRequest WHERE RequestSend = :RequestSend AND RequestReceive = :RequestReceive");
      $existingRequest->bindParam(':RequestSend', $RequestSend);
      $existingRequest->bindParam(':RequestReceive', $FriendUserID);
      $existingRequest->execute();

      if ($existingRequest->rowCount() > 0) {
        echo "Friend request already sent!";
      } else {
        // Insert a new friend request into the database
        $insertRequest = $pdo->prepare("INSERT INTO FriendRequest (RequestSend, RequestReceive, Status) VALUES (:RequestSend, :RequestReceive, 'pending')");
        $insertRequest->bindParam(':RequestSend', $RequestSend);
        $insertRequest->bindParam(':RequestReceive', $FriendUserID);
        $insertRequest->execute();

        echo "Friend request sent successfully!";
      }
    } else {
      echo "Sorry, this user was not found in your system.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

$pdo = null;