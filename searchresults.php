<?php
require_once 'login.php';
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_term = $_POST['search_term'];
    
    include 'logindb.php'; #the file with the database connection

    #queries the database to search for other users
    $sql = "SELECT * FROM User WHERE Username LIKE '%$search_term%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h3>{$row['Username']}</h3>";
            echo "<p>Email: {$row['Email']}</p>";
            echo "<p>FirstName: {$row['FirstName']}</p>";
            echo "<p>LastName: {$row['LaststName']}</p>";
            echo "<p>Zip: {$row['Zip']}</p>";
        }
    } else {
        echo "No matching users found.";
    }

    $conn->close();
} else {
    header("Location: profile.php");
    exit();
}
?>