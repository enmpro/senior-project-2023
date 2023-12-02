<?php
require_once 'logindb.php';

$ReceiverUserID = $AuthUserID;

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
        $SenderUserID = $row['RequestSend']
        echo "User $SenderUserID wants to be your friend!
             <form action = 'accept_request.php' method = 'post'>
                <input type = 'hidden' name = 'RequestID' value = '{$row['UserID']}'>
                <button type = 'submit'>Accept</button>
             </form>
             <form action = 'reject_request.php' method = 'post'>
                <input type = 'hidden' name = 'RequestID' value = '{$row['UserID']}'>
                <button type = 'submit'>Reject</button>
             </form><br>";
              
} 

$conn = null;


?>
