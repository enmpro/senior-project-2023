<?php
require_once 'logindb.php';


try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

$eventID = $_POST['id'];

$query = "DELETE FROM Event WHERE EventID = $eventID";
$result = $pdo->query($query);

header('Location: event_coord.php');

?>