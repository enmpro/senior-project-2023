<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    $RequestID = test_userinput($_GET['UserID']);

    try {
        $updateRequest = $pdo->prepare("UPDATE FriendRequest SET Status = 'accepted' 
                                        WHERE RequestID = :RequestID AND Status = 'pending'");
        $updateRequest->bindParam(':RequestID', $RequestID);
        $updateRequest->execute();

        $rowsAffected = $updateRequest->rowCount();

        if ($rowsAffected > 0) {
            $getRequestInfo = $pdo->prepare("SELECT RequestSend, RequestReceive FROM FriendRequest
                                             WHERE RequestID = :RequestID");
            $getRequestInfo->bindParam(':RequestID', $RequestID);
            $getRequestInfo->execute();
            $request_info = $getRequestInfo->fetch(PDO::FETCH_ASSOC);

            // Redirect the user back to display_requests.php with a success message
            header('Location: display_requests.php?success=1');
            exit;
        } else {
            // No rows were updated, meaning the request might not be pending
            header('Location: display_requests.php?error=1');
            exit;
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
}

$pdo = null;
?>
