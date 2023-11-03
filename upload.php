<?php
if (isset($_POST["submit"])) {
    $targetDirectory = "userphoto/"; // Directory to store profile pictures
    $targetFile = $targetDirectory . basename($_FILES["profilePicture"]["name"]);

    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
        echo "Profile picture uploaded successfully.";
    } else {
        echo "Error uploading profile picture.";
    }
}


?>