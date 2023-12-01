<?php
require_once 'logindb.php';

$SenderUserID = $AuthUserID;

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


function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


try {
    $stmt = $conn->prepare("SELECT * FROM FriendRequest WHERE RequestReceive = :RequestReceive AND Status = 'pending'");
    $stmt->bindParam(':RequestReceive', $SenderUserID);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>{$row['RequestSend']} wants to be your friend! 
              <a href='accept_request.php?id={$row['id']}'>Accept</a> 
              <a href='reject_request.php?id={$row['id']}'>Reject</a></li>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;


?>
