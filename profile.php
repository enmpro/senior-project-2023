<?php
require_once 'login.php';

session_start();
if (!isset($_SESSION['user_name'])) {
    // The user is not logged in, redirect them to the login page
    header('Location: main.php');
    exit;
}

$username = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

$query  = "SELECT * FROM User WHERE UserID LIKE $user_id";
$result = $pdo->query($query);

if ($row = $result->fetch()) {
  $fullname = $row['FirstName'].' '.$row['LastName'];
  $gender = $row['Gender'];
  $zip = $row['Zip'];
  $birthday = $row['Birthday'];
}

$query2  = "SELECT * FROM Profile WHERE UserID LIKE $user_id";
$result2 = $pdo->query($query2);

if ($row = $result->fetch()) {
  $description = $row['Description'];
  $showgender = $row['ShowGender'];
  $showlocation = $row['ShowLocation'];
  $showPic = $row['ProfilePic'];
  header("Content-Type: image/jpeg");
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <a href="community.php">Community</a>-
    </div>
</nav>

  <div class="card container-fluid w-75 mt-5">
    <h1><?php echo $username;?></h1>

    <section>
      <img src="<?php echo $showPic ?>" alt="Profile Image" class="profile-image">
      <h2><?php echo $fullname;?></h2>
      <p>Musician | Music Enthusiast</p>
    </section>
    <section>
      <h2>About Me</h2>
      <p>Description:
      <?php echo $description;?>
      </p>
      <p>Gender:
      <?php echo $gender;?>
      </p>
      <p>Birthday:
      <?php echo $birthday;?>
      </p>
    </section>
    <section>
      <h2>Top Music</h2>
      <p>
        FIXME
      </p>
    </section>
    <section>
      <h2>Social Media</h2>
      <p>
        <?php 
        $query3  = "SELECT * FROM SocialMediaHandles WHERE ProfileID LIKE 
        (SELECT ProfileID FROM Profile WHERE UserID LIKE $user_id)";
        $result = $pdo->query($query3);
        
        while ($row = $result->fetch()) {
          $handle_label = $row['Platform'];
          $handle = $row['Handle'];
          $url = $row['URL'];

          echo ' '.$handle_label.' '.$handle.' '.$url . '<br>';
        }
        ?>
      </p>
    </section>
  </div>
  <div class="container text-center mt-5">
    <a class="btn btn-primary submit-btn" href="profile_edit.html">Edit Profile</a>
</div>

</body>

</html>