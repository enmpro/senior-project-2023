<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>

<h1>Search Results</h1>

<?php
    #connect to database
    require_once 'logindb.php'; 
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search_term = htmlspecialchars($_POST['search_term']);

        #sql query
        $sql = "SELECT * FROM Users WHERE Username LIKE '%$search_term%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "Username: " . $row['Username'] . "<br>";
            }
        } else {
            echo "No matching users found.";
        }
    } else {
        header("Location: searchusers.html");
        exit(); #ensures that the user exits after redirect
    }

    $conn->close();
?>

</body>
</html>
