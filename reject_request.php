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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['RequestID'])) {
    $RequestID = test_userinput($_POST['RequestID']);

    try {
        $updateRequest = $pdo->prepare("UPDATE FriendRequest SET Status = 'rejected' 
                                        WHERE RequestID = :RequestID AND Status = 'pending'");
        $updateRequest->bindParam(':RequestID', $RequestID);
        $updateRequest->execute();

        $rowsAffected = $updateRequest->rowCount();

        if ($rowsAffected > 0) {
            header('Location: display_requests.php?success=1');
            exit;
        } else {
            header('Location: display_requests.php?error=1');
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $pdo = null;
    }
}
?>
