<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

session_start();
if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: landing.html');
    exit;
}

$userID = $_SESSION['user_id'];

function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search</title>
</head>
<body>

    <h2>User Search</h2>

    <!-- Search Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="search">Search for a user:</label>
        <input type="text" name="search" id="search" required>
        <button type="submit">Search</button>
    </form>

    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the search query
        $search = test_userinput($_POST["search"]);

        
        $sql = "SELECT * FROM User WHERE Username LIKE '%$search%'";
        $result = $pdo->query($sql);

        foreach ($result as $row) {
            echo "Username: " . $row['Username'] . "<br>";
        }
    }

    
    ?>

</body>
</html>
