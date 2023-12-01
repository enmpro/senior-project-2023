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

    $locationOnOff = $_POST['location-chk'];
    $genderOnOff = $_POST['location-chk'];
    $birthdayOnOff = $_POST['location-chk'];

    if ($_POST['description'] == '') {
        $oldDescQuery = "SELECT Description FROM Profile WHERE UserID = :userID";
        $oldDescStmt = $pdo->prepare($oldDescQuery);
        $oldDescStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $oldDescStmt->execute();
        $oldDesc = $oldDescStmt->fetchColumn();
        $description = "$oldDesc";
    } else {
        $description = test_userinput($_POST['description']);
    }

    if ($_POST['Email'] == '') {
        $oldEmailQuery = "SELECT Email FROM User WHERE UserID = :userID";
        $oldEmailStmt = $pdo->prepare($oldEmailQuery);
        $oldEmailStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $oldEmailStmt->execute();
        $oldEmail = $oldEmailStmt->fetchColumn();
        $email = "$oldEmail";
    } else {
        $email = test_userinput($_POST['Email']);
    }


    if ($_POST['facebook'] == '') {
        $oldFaceQuery = "SELECT URL FROM SocialMediaHandles 
        WHERE Platform = 'Facebook' AND
        ProfileID = (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";
        $oldFaceStmt = $pdo->prepare($oldFaceQuery);
        $oldFaceStmt->bindParam(':userid', $userID, PDO::PARAM_INT);
        $oldFaceStmt->execute();
        $oldFace = $oldFaceStmt->fetchColumn();
        $facebook = "$oldFace";
    } else {
        $facebook = test_userinput($_POST['facebook']);
    }

    if ($_POST['twitter'] == '') {
        $oldTwitQuery = "SELECT URL FROM SocialMediaHandles 
        WHERE Platform = 'Twitter' AND
        ProfileID = (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";
        $oldTwitStmt = $pdo->prepare($oldTwitQuery);
        $oldTwitStmt->bindParam(':userid', $userID, PDO::PARAM_INT);
        $oldTwitStmt->execute();
        $oldTwit = $oldTwitStmt->fetchColumn();
        $twitter = "$oldTwit";
    } else {
        $twitter = test_userinput($_POST['twitter']);
    }

    if ($_POST['instagram'] == '') {
        $oldInstaQuery = "SELECT URL FROM SocialMediaHandles 
        WHERE Platform = 'Instagram' AND
        ProfileID = (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";
        $oldInstaStmt = $pdo->prepare($oldInstaQuery);
        $oldInstaStmt->bindParam(':userid', $userID, PDO::PARAM_INT);
        $oldInstaStmt->execute();
        $oldInsta = $oldInstaStmt->fetchColumn();
        $instagram = "$oldInsta";
    } else {
        $instagram = test_userinput($_POST['instagram']);
    }

    if (isset($_POST['submit'])) {

        $oldPhotoQuery = "SELECT ProfilePic FROM Profile WHERE UserID = :userID";
        $oldPhotoStmt = $pdo->prepare($oldPhotoQuery);
        $oldPhotoStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $oldPhotoStmt->execute();
        $oldPhoto = $oldPhotoStmt->fetchColumn();

        if ($_FILES['userphoto']['error'] == 4) {
            $targetPhotoFile = "$oldPhoto";
        } else {
            if (!empty($oldPhoto)) {
                // Construct the path to the old photo file (adjust the path as needed)
                $oldPhotoPath = "$oldPhoto";

                // Check if the file exists before attempting to delete
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath); // Delete the old photo file
                }
            }
            $targetDirectory = "userphoto/"; // Directory to store profile pictures
            $randomFileName = uniqid();
            $targetPhotoFile = $targetDirectory . $randomFileName . '_' . basename($_FILES['userphoto']['name']);
            echo <<<_END
                        <script>
                        alert("$targetPhotoFile");                   
                        </script>
                        _END;

            if (move_uploaded_file($_FILES['userphoto']['tmp_name'], $targetPhotoFile)) {
                echo <<<_END
                        <script>
                            alert("Photo added");
                            
                        </script>
                        _END;
            } else {
                echo <<<_END
                        <script>
                            alert("Photo not added");
                            
                        </script>
                        _END;

                $targetPhotoFile = '';
            }
        }
    }


    $editUser = "UPDATE User
                   SET Email = :newEmail
                   WHERE UserID = :userid";
    $stmtUser = $pdo->prepare($editUser);
    $stmtUser->bindParam(':newEmail', $email, PDO::PARAM_STR);
    $stmtUser->bindParam(':userid', $userid, PDO::PARAM_STR, 11);
    $stmtUser->execute();

    $editProfile = "UPDATE User
    SET ShowGender = :genderChk,
        ShowLocation = :locationChk,
        ShowBirthday = :birthChk
        WHERE UserID = :userid";
    $stmtProfile = $pdo->prepare($editProfile);
    $stmtProfile->bindParam(':userid', $userid, PDO::PARAM_STR, 11);
    $stmtProfile->bindParam(':genderChk', $genderOnOff, PDO::PARAM_STR, 11);
    $stmtProfile->bindParam(':locationChk', $locationOnOff, PDO::PARAM_STR, 11);
    $stmtProfile->bindParam(':birthChk', $birthdayOnOff, PDO::PARAM_STR, 11);
    $stmtProfile->execute();

    alter_social($pdo, $facebook, 'Facebook', $userID);
    alter_social($pdo, $twitter, 'Twitter', $userID);
    alter_social($pdo, $instagram, 'Instagram', $userID);
    alter_profile($pdo, $description, $targetPhotoFile, $userID);

    header('Location: profile.php');

}


function alter_social($pdo, $newUrl, $platform, $userid)
{

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

function alter_profile($pdo, $description, $userPhoto, $userid)
{

    $editProfile = "UPDATE Profile
                   SET Description = :newDesc,
                   ProfilePic = :newPic
                   WHERE UserID = :userid";
    $stmtProfile = $pdo->prepare($editProfile);

    $stmtProfile->bindParam(':newDesc', $description, PDO::PARAM_STR);
    $stmtProfile->bindParam(':newPic', $userPhoto, PDO::PARAM_STR);
    $stmtProfile->bindParam(':userid', $userid, PDO::PARAM_STR, 11);

    $stmtProfile->execute();
}