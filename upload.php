<?php
if (isset($_POST["submit"])) {
    // Check if a file was uploaded without errors
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $localFile = $_FILES["fileToUpload"]["tmp_name"]; // Temporary uploaded file
        $fileName = $_FILES["fileToUpload"]["name"]; // Original file name

        echo $localFile. " ";
        echo $fileName;

        $ftpServer = "ftp.cantio.live";
        $ftpUsername = "enmanuel@cantio.live";
        $ftpPassword = "VsCfKTDy9m@N";

        // Connect to the FTP server
        $ftpConnection = ftp_connect($ftpServer);
        if ($ftpConnection === false) {
            die("Could not connect to the FTP server");
        } else {
            echo "connected";
        }

        $login_result = ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
        // Login to the FTP server
        if (!$login_result) {
            die("FTP login failed");
        } else {
            echo "connected";
        }

        // Set transfer mode to binary for image files
        ftp_set_option($ftpConnection, FTP_BINARY, true);

        $remoteDirectory = "/public_html/userphoto/"; // Replace with the remote directory path

        // Generate a unique remote file name using timestamp
        $remoteFile = $remoteDirectory . time() . "_" . $fileName;

        echo $remoteFile;

        // Upload the file to the FTP server
        if (ftp_put($ftpConnection, $remoteFile, $localFile, FTP_BINARY)) {
            echo "File uploaded successfully";
        } else {
            echo "File upload failed";
        }

        $file = "localfile.txt";

        // upload file
        if (ftp_put($ftpConnection, "serverfile.txt", $file, FTP_ASCII)) {
            echo "Successfully uploaded $file.";
        } else {
            echo "Error uploading $file.";
        }


        // Close the FTP connection
        ftp_close($ftpConnection);
    } else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }
}
?>