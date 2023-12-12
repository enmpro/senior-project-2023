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

$eventID = $_POST['event_id'];

$deleteSql = "DELETE FROM UserRSVP WHERE EventID = $eventID and UserID = $userID";
$deleteRsvp = $pdo->query($deleteSql);

$deleteValueSQL = "UPDATE Event SET UserNumAttend = UserNumAttend - 1 WHERE EventID = :eventID";
$deleteValueStmt = $pdo->prepare($deleteValueSQL);
$deleteValueStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
$deleteValueStmt->execute();

header('Location: profile.php');


?>