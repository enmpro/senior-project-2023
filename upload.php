<?php
if (isset($_POST["submit"])) {
    // Check if a file was uploaded without errors
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {

        $ftpServer = "ftp.cantio.live";
        $ftpUsername = "enmanuel@cantio.live";
        $ftpPassword = "VsCfKTDy9m@N";

        $remote_dir = '/public_html/userphoto/';
        $src_file = $_FILES["fileToUpload"]["name"]; // Original file name

        if ($src_file != '') {
            // remote file path
            $dst_file = $remote_dir . $src_file;

            // connect ftp
            $ftpcon = ftp_connect($ftpServer) or die('Error connecting to ftp server...');

            // ftp login
            $ftplogin = ftp_login($ftpcon, $ftpUsername, $ftpPassword);

            // ftp upload
            if (ftp_put($ftpcon, $dst_file, $src_file, FTP_ASCII))
                echo 'File uploaded successfully to FTP server!';
            else
                echo 'Error uploading file! Please try again later.';

            // close ftp stream
            ftp_close($ftpcon);
        }


        // // Connect to the FTP server
        // $ftpConnection = ftp_connect($ftpServer);
        // if ($ftpConnection === false) {
        //     die("Could not connect to the FTP server");
        // } else {
        //     echo "connected";
        // }

        // $login_result = ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
        // // Login to the FTP server
        // if (!$login_result) {
        //     die("FTP login failed");
        // } else {
        //     echo "connected";
        // }

        // // Set transfer mode to binary for image files
        // ftp_set_option($ftpConnection, FTP_BINARY, true);

        // $remoteDirectory = "/public_html/userphoto/"; // Replace with the remote directory path

        // // Generate a unique remote file name using timestamp
        // $remoteFile = $remoteDirectory . time() . "_" . $fileName;

        // echo $remoteFile;

        // // Upload the file to the FTP server
        // if (ftp_put($ftpConnection, $remoteFile, $localFile, FTP_BINARY)) {
        //     echo "File uploaded successfully";
        // } else {
        //     echo "File upload failed";
        // }

        // // Close the FTP connection
        // ftp_close($ftpConnection);
    } else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }
}
?>