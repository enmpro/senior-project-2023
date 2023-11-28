<?php
require_once 'login.php';


session_start();
if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: landing.html');
    exit;
}

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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Results</title>
</head>

<body>

    <h1>Search Results</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search_username = test_userinput($_POST['search_username']);

        #sql query
    
        $sql = "SELECT * FROM User WHERE Username LIKE '%$userID%'";
        $result = $pdo->query($sql);


        if ($row = $result->fetch()) {
            echo "Username: " . $row['Username'] . "<br>";
        }

        
    } else {
        header("Location: searchusers.html");
        exit(); #ensures that the user exits after redirect
    }
    ?>

</body>

</html>