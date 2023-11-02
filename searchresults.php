<?php
session_start(); 

if (isset($_POST['search_term']) && !empty($_POST['search_term'])) {
    $search_term = htmlspecialchars($_POST['search_term']);

    
    include 'logindb.php'; #the file with the database connection

    #queries the database to search for other users
    $sql = "SELECT * FROM User WHERE Username LIKE '%$search_term%'"; /

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {
            echo "Username: " . $row['Username'] . "<br>";
        }
    } else {
        echo "No results found";
    }

    $conn->close();
} else {
    echo "Please enter a search term";
}
?>