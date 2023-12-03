<?php
require_once 'logindb.php';

$ReceiverUserID = $AuthUserID;
$CurrentUserID = $AuthUserID;

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
    $pdo = new PDO($attr, $user, $pass, $opts);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result = $pdo->prepare("SELECT * FROM FriendRequest WHERE RequestReceive = :RequestReceive AND Status = 'pending'");
    $result->bindParam(':RequestReceive', $CurrentUserID);
    $result->execute();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $SenderUserID = $row['RequestSend'];
        echo "<li>{$SenderUserID} wants to be your friend! 
          <a href='accept_request.php?id={$row['id']}'>Accept</a> 
          <a href='reject_request.php?id={$row['id']}'>Reject</a></li>";
    }
}
catch (PDOExceotion $e){
    echo "Error: ". $e->getMessage();
}

$pdo = null;


?>
