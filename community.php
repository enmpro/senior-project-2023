<?php
require_once 'login.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: main.php');
    exit;
}

$username = $_SESSION['user_id'];


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Community</title>
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <p class="navbar-brand">
                CANTIO
            </p>
            <a href="homepage.php">Home</a>
            <a href="#">About</a>
            <a href="profile.html">Profile</a>
            <a href="community.php">Community</a>
        </div>
    </nav>
    <div>
        <p>THIS IS WHERE THE COMMUNITY PAGE IS</p>
    </div>
</body>

</html>