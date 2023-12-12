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

    $eventID = $_POST['event_id'];

    $checkUserRSVP = "SELECT * From UserRSVP WHERE UserID = :userID AND EventID = :eventID";
    $resultUserRSVP = $pdo->prepare($checkUserRSVP);
    $resultUserRSVP->bindParam(':userID', $userID, PDO::PARAM_INT);
    $resultUserRSVP->bindParam(':eventID', $eventID, PDO::PARAM_INT);
    $resultUserRSVP->execute();

    if ($resultUserRSVP->rowCount() > 0) {

        if (isset($_POST['eventStatus']) == "Interested") {

            $interestedStat = "UPDATE UserRSVP SET RSVPStatus = 'Interested' WHERE UserID = :userID AND EventID = :eventID";
            $interestedStmt = $pdo->prepare($interestedStat);
            $interestedStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $interestedStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
            $interestedStmt->execute();

            $interestValueSQL = "UPDATE Event SET UserNumAttend = UserNumAttend - 1 WHERE EventID = :eventID";
            $interestValueStmt = $pdo->prepare($interestValueSQL);
            $interestValueStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
            $interestValueStmt->execute();
        }

        if (isset($_POST['eventStatus']) == "Attending") {

            $attendStat = "UPDATE UserRSVP SET RSVPStatus = 'Attending' WHERE UserID = :userID AND EventID = :eventID";
            $attendStmt = $pdo->prepare($attendStat);
            $attendStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $attendStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
            $attendStmt->execute();
            
            $attendValueSQL = "UPDATE Event SET UserNumAttend = UserNumAttend + 1 WHERE EventID = :eventID";
            $attendValueStmt = $pdo->prepare($attendValueSQL);
            $attendValueStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
            $attendValueStmt->execute();
        }

    }

}

header('Location: profile.php');

?>
