<?php
// 
// Handle responses from Spotify API
//
// Code by Hanbi Hanz Choi
//


if (!empty($_GET['code'])) 
    include '../inc/requestLogIn.php';
    echo '<pre>';
    print_r($_GET['code']);
    echo '</pre>';
    
else {
    echo '<pre>';
    print_r($_GET);
    echo '</pre>';
}