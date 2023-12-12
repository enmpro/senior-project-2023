<?php
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


session_start();

if (isset($_SESSION['user_id'])) {

    $userID = $_SESSION['user_id'];
}


$query = "SELECT * FROM User WHERE UserID LIKE $userID";
$result = $pdo->query($query);

if ($row = $result->fetch()) {
    $fullname = $row['FirstName'] . ' ' . $row['LastName'];
    $gender = $row['Gender'];
    $zip = $row['Zip'];
    $birthday = $row['Birthday'];
    $userEmail = $row['Email'];
}

$query2 = "SELECT * FROM Profile WHERE UserID LIKE $userID";
$result2 = $pdo->query($query2);

if ($row2 = $result2->fetch()) {
    $description = $row2['Description'];
    $showgender = $row2['ShowGender'];
    $showlocation = $row2['ShowLocation'];
    $showbirthday = $row2['ShowBirthday'];
    $profilePic = $row2['ProfilePic'];
}

$query3 = "SELECT * FROM EventOrganizer WHERE UserID LIKE $userID";
$result3 = $pdo->query($query3);

if ($row3 = $result3->fetch()) {
  $organizerBool = true;
} else {
  $organizerBool = false;
}


$oldFaceQuery = "SELECT URL FROM SocialMediaHandles 
        WHERE Platform = 'Facebook' AND
        ProfileID = (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";
$oldFaceStmt = $pdo->prepare($oldFaceQuery);
$oldFaceStmt->bindParam(':userid', $userID, PDO::PARAM_INT);
$oldFaceStmt->execute();
$oldFace = $oldFaceStmt->fetchColumn();
$facebook = "$oldFace";

$oldTwitQuery = "SELECT URL FROM SocialMediaHandles 
        WHERE Platform = 'Twitter' AND
        ProfileID = (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";

$oldTwitStmt = $pdo->prepare($oldTwitQuery);
$oldTwitStmt->bindParam(':userid', $userID, PDO::PARAM_INT);
$oldTwitStmt->execute();
$oldTwit = $oldTwitStmt->fetchColumn();
$twitter = "$oldTwit";
$oldInstaQuery = "SELECT URL FROM SocialMediaHandles 
        WHERE Platform = 'Instagram' AND
        ProfileID = (SELECT ProfileID FROM Profile WHERE UserID LIKE :userid)";

$oldInstaStmt = $pdo->prepare($oldInstaQuery);
$oldInstaStmt->bindParam(':userid', $userID, PDO::PARAM_INT);
$oldInstaStmt->execute();
$oldInsta = $oldInstaStmt->fetchColumn();
$instagram = "$oldInsta";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<style>
    /* Add custom styles here, if needed */
    body {
        padding-top: 100px;
        /* Adjust for fixed navbar height */
    }

    .homepage-section {
        padding: 60px 0;
    }
</style>


<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">CANTIO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-center justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="homepage.php">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spotify/explore_page.php">Explore Music</a>
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
                <div class="my-3 mx-4">
                    <form method="post" action="user_logout.php">
                        <button class="btn btn-secondary" type="submit" name="logout">Log Out</button>

                    </form>
                </div>
            </div>

        </div>
    </nav>
    <div class="container py-5 h-100">
        <div class="card shadow border-primary border-5">
            <div class="card-body">
                <h2 class="text-center mb-4">Edit Profile Details</h2>
                <form action="profile_edit.php" method="post" enctype="multipart/form-data">
                    <div class="mb-4 pb-2 form-floating">
                        <textarea class="form-control" name="description" id="description"
                            placeholder="#"><?php echo $description; ?></textarea>
                        <label class="form-label" for="description">
                            Description
                        </label>
                    </div>
                    <div class="mb-4 pb-2 form-floating">
                        <input class="form-control" type="email" name="Email" id="Email"
                            placeholder="#" <?php echo "value=". "'". $userEmail . "'"; ?>> 
                        <label class="form-label" for="Email">
                            Email
                        </label>
                    </div>
                    <div class="mb-4 pb-2 input-group">
                        <label class="input-group-text" for="userphoto">
                            Profile Picture
                        </label>
                        <input class="form-control" type="file" name="userphoto" id="userphoto"
                            placeholder="#">
                    </div>
                    <h3 class="text-center">Social Media</h3>
                    <div class="mb-4 pb-2 form-floating">
                        <input class="form-control" type="url" id="facebook" name="facebook"
                            placeholder="#" <?php echo "value=" . "'". $facebook . "'"; ?>>
                        <label class="form-label" for="facebook">Facebook</label>
                    </div>
                    <div class="mb-4 pb-2 form-floating">
                        <input class="form-control" type="url" id="twitter" name="twitter"
                            placeholder="#"  <?php echo "value=" . "'". $twitter . "'"; ?>>
                        <label class="form-label" for="twitter">X</label>
                    </div>
                    <div class="mb-4 pb-2 form-floating">
                        <input class="form-control" type="url" id="instagram" name="instagram"
                            placeholder="#" <?php echo "value=" . "'". $instagram . "'"; ?>>
                        <label class="form-label" for="instagram">Instagram</label>
                    </div>
            </div>
            <div class="m-auto">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="gender-chk" name="gender-chk" value="yes"
                    <?php if($showgender == 'yes') {
                        echo ' checked';
                    } 
                    ?>>
                    <label class="form-check-label" for="gender-chk">Show gender</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="location-chk" name="location-chk" value="yes"
                    <?php if($showlocation == 'yes') {
                        echo ' checked';
                    } 
                    ?>>
                    <label class="form-check-label" for="location-chk">Show location</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="age-chk" name="age-chk" value="yes"
                    <?php if($showbirthday == 'yes') {
                        echo ' checked';
                    } 
                    ?>>
                    <label class="form-check-label" for="age-chk">Show age</label>
                </div>
            </div>

            <div class="text-center">
                <input class="my-3 btn btn-primary login-btn" type="submit" name="submit" value="Save Changes">
            </div>
            </form>
        </div>

    </div>
    </div>


    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>