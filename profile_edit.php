<?php
require_once 'logindb.php';
require_once 'login.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: main.php');
    exit;
}

$username = $_SESSION['user_id'];
$user_id = $_SESSION['user_num'];


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

    alter_social($pdo, $facebook, 'Facebook', $user_id);
    alter_social($pdo, $twitter, 'Twitter', $user_id);
    alter_social($pdo, $instagram, 'Instagram', $user_id);


}


function alter_social($pdo, $newUrl, $platform, $userid) {
    
    $editProfile = "UPDATE SocialMediaHandles
                   SET Url = :newUrl
                   WHERE Platform = :platform AND ProfileID = 
                   (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";
    $stmtProfile = $pdo->prepare($editProfile);

    $stmtProfile->bindParam(':newUrl', $newUrl, PDO::PARAM_STR, 255);
    $stmtProfile->bindParam(':platform', $platform, PDO::PARAM_STR, 50);
    $stmtProfile->bindParam(':userid', $userid, PDO::PARAM_STR, 11);
    
    $stmtProfile->execute();
}