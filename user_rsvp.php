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

$userID = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventID = $_POST['event_id']; // Replace with the actual event ID

    // Get the RSVP status from the form
    $rsvpStatus = $_POST['rsvp_status'];


    // Delete the previous record for the same user and event if it exists
    $deleteSql = "DELETE FROM UserRSVP WHERE UserID = :userID AND EventID = :eventID";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $deleteStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);

    // Execute the delete query only if the record exists
    if ($deleteStmt->execute()) {
        // Insert the new RSVP record
        $insertSql = "INSERT INTO UserRSVP (UserID, EventID, RSVPStatus)
                      VALUES (:userID, :eventID, :rsvpStatus)";

        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $insertStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
        $insertStmt->bindParam(':rsvpStatus', $rsvpStatus, PDO::PARAM_STR);

        if ($insertStmt->execute()) {
            echo "RSVP successful!";
        } else {
            echo "Error processing RSVP.";
        }
    } else {
        echo "Error deleting previous RSVP record or record does not exist.";
    }
}

header('Location: user_event.php');
?>