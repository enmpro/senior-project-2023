<?php
require_once 'logindb.php';


try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}




$oldPhotoQuery = "SELECT EventPhoto FROM Event WHERE EventID = 8";
$oldPhotoStmt = $pdo->prepare($oldPhotoQuery);
$oldPhotoStmt->execute();
$oldPhoto = $oldPhotoStmt->fetchColumn();

echo $oldPhoto;


?>