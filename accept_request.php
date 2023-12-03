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
function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_GET['UserID'])) {
    $RequestID = $_GET['UserID'];

    try {
       
        $updateRequest = $pdo->prepare("UPDATE FriendRequest SET Status = 'accepted' 
                                        WHERE UserID = :UserID");
        $updateRequest->bindParam(':UserID', $RequestID);
        $updateRequest->execute();

        $getRequestInfo = $pdo->prepare("SELECT RequestSend, RequestReceive FROM FriendRequest
                                         WHERE UserID = :UserID");
        $getRequestInfo->bindParam(':UserID', $RequestID);
        $getRequestInfo->execute();
        $request_info = $getRequestInfo->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
}

$pdo = null;
?>
