<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


session_start();

if (isset($_SESSION['user_id'])) {

    $userID = $_SESSION['user_id'];
}


function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $description = test_userinput($_POST["description"]);
    $facebook = test_userinput($_POST['facebook']);
    $twitter = test_userinput($_POST['twitter']);
    $instagram = test_userinput($_POST['instagram']);

    alter_social($pdo, $facebook, 'Facebook', $userID);
    alter_social($pdo, $twitter, 'Twitter', $userID);
    alter_social($pdo, $instagram, 'Instagram', $userID);

    header('Location: profile.php');

}


function alter_social($pdo, $newUrl, $platform, $userid) {
    
    $editProfile = "UPDATE SocialMediaHandles
                   SET Url = :newUrl
                   WHERE Platform LIKE :platform AND ProfileID LIKE 
                   (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";
    $stmtProfile = $pdo->prepare($editProfile);

    $stmtProfile->bindParam(':newUrl', $newUrl, PDO::PARAM_STR, 255);
    $stmtProfile->bindParam(':platform', $platform, PDO::PARAM_STR, 50);
    $stmtProfile->bindParam(':userid', $userid, PDO::PARAM_STR, 11);
    
    $stmtProfile->execute();
}