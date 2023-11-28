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
$result = $sql("SELECT * FROM FriendRequest WHERE RequestRecieve = $sender_user_id AND status = 'pending'");

while ($row = $result->fetch_assoc()) {
    echo "<li>{$row['RequestSend']} wants to be your friend! 
          <a href='accept_request.php?id={$row['id']}'>Accept</a> 
          <a href='reject_request.php?id={$row['id']}'>Reject</a></li>";
}
?>
