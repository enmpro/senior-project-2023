<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
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
    
    

}


function update_social($pdo, $handleID, $profileID, $platform, $handle, $url) {
    
    $sqlProfile = "INSERT INTO SocialMediaHandles (HandleID, ProfileID, Platform, Handle, URL) 
                   VALUES (:handleID, :profileID, :platform, :handle, :url)";
    $stmtProfile = $pdo->prepare($sqlProfile);

    $stmtProfile->bindParam(':handleID', $handleID, PDO::PARAM_STR, 11);
    $stmtProfile->bindParam(':profileID', $profileID, PDO::PARAM_STR, 11);
    $stmtProfile->bindParam(':platform', $platform, PDO::PARAM_STR, 50);
    $stmtProfile->bindParam(':handle', $handle, PDO::PARAM_STR, 100);
    $stmtProfile->bindParam(':url', $url, PDO::PARAM_STR, 255);
    
    $stmtProfile->execute();
}