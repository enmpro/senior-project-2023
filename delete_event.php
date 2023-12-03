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

if (isset($_SESSION['user_id'])) {

    $userID = $_SESSION['user_id'];
}

$eventID = $_POST['id'];

$query = "DELETE FROM Event WHERE EventID = $eventID";
$result = $pdo->query($query);

$deleteSql = "DELETE FROM UserRSVP WHERE EventID = $eventID AND UserID = $userID";
$deleteRsvp = $pdo->prepare($deleteSql);

header('Location: event_coord.php');

?>