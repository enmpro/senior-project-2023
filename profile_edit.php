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


    $description = test_userinput($_POST['description']);
    $facebook = test_userinput($_POST['facebook']);
    $twitter = test_userinput($_POST['twitter']);
    $instagram = test_userinput($_POST['instagram']);

    if (isset($_POST['submit'])) {

        $oldPhotoQuery = "SELECT ProfilePic FROM Profile WHERE UserID = :userID";
        $oldPhotoStmt = $pdo->prepare($oldPhotoQuery);
        $oldPhotoStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $oldPhotoStmt->execute();
        $oldPhoto = $oldPhotoStmt->fetchColumn();


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

    alter_social($pdo, $facebook, 'Facebook', $userID);
    alter_social($pdo, $twitter, 'Twitter', $userID);
    alter_social($pdo, $instagram, 'Instagram', $userID);
    alter_profile($pdo, $description, $targetPhotoFile, $userID);

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

function alter_profile($pdo, $description, $userPhoto, $userid) {
    
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