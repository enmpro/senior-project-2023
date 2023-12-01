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

$query3 = "SELECT * FROM EventOrganizer WHERE UserID LIKE $user_id";
$result3 = $pdo->query($query3);

if ($row3 = $result3->fetch()) {
    $organizerBool = true;
} else {
    $organizerBool = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Search</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CANTIO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="community.php">Community</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_event.php">Event</a>
                    </li>
                    <?php
                    if ($organizerBool) {
                        echo <<<_END
                    <li class="nav-item">
                        <a class="nav-link" href="event_coord.php">Event Coordinator</a>
                    </li>
                    _END;
                    }
                    ?>

                </ul>
                <div>
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>

    <h2>User Search</h2>

    <!-- Search Form -->
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="search">Search for a user:</label>
        <input type="text" name="search" id="search" <?php
        if (isset($_GET['search'])) {
            $search = test_userinput($_GET["search"]);
            echo "value=" . "'" . $search . "'";
        }
        ?>>

        <button type="submit">Search</button>
    </form>
    <div class="container">
        <div class="row">

            <?php
            // Check if the form is submitted
            if (isset($_GET['search'])) {
                $search = test_userinput($_GET["search"]);

                if ($search == '') {
                    echo "<p>No results found.</p>";

                } else {
                    $search = test_userinput($_GET["search"]);

                    $sql = "SELECT * FROM User WHERE Username LIKE '%$search%'";
                    $result = $pdo->query($sql);

                    foreach ($result as $row) {


                        ?>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <img class="rounded" src="https://via.placeholder.com/150" alt="profile">
                                    <h3><?php echo $row['Username']; ?></h3>
                                    <h6><?php echo $row['Firstname'] . " " . $row['LastName']; ?></h6>
                                    <h6><i>Event Coordinator</i></h6>
                                    <button class="btn btn-success">Add Friend</button>
                                </div>
                            </div>
                        </div>
                        <?php
                        echo "Username: " . $row['Username'] . "<br>";
                        ?>
                        <div>
                            <form action="user_view.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $row['UserID']; ?>">
                                <button class="btn btn-secondary" type="submit">View Profile</button>
                            </form>

                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
exit(); // Ensure that no further content is sent
?>