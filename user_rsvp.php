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

    // Insert or update the RSVP status in the UserRSVP table
    $sql = "INSERT INTO UserRSVP (UserID, EventID, RSVPStatus)
            VALUES (:userID, :eventID, :rsvpStatus)
            ON DUPLICATE KEY UPDATE RSVPStatus = :rsvpStatus";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT, 11);
    $stmt->bindParam(':eventID', $eventID, PDO::PARAM_INT, 11);
    $stmt->bindParam(':rsvpStatus', $rsvpStatus, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "RSVP successful!";
    } else {
        echo "Error processing RSVP.";
    }
}

header('Location: user_event.php');
?>