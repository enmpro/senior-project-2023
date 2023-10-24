<?php
// 
// This is Music Utopia
// Initial app index file
//
// Code by Hanbi Hanz Choi
//

// Include required files
//require 'private/global.inc.php';
require 'inc/curl.class.php';

// Check to see if user session has been created
if (!empty($_SESSION['spotify_token'])) {
    include 'inc/dashboard.php';
} else {
    include 'inc/home.php';
}