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

$query = "SELECT * FROM User WHERE UserID LIKE $user_id";
$result = $pdo->query($query);

if ($row = $result->fetch()) {
  $fullname = $row['FirstName'] . ' ' . $row['LastName'];
  $gender = $row['Gender'];
  $zip = $row['Zip'];
  $birthday = $row['Birthday'];
}

$query2 = "SELECT * FROM Profile WHERE UserID LIKE $user_id";
$result2 = $pdo->query($query2);

if ($row2 = $result2->fetch()) {
  $description = $row2['Description'];
  $showgender = $row2['ShowGender'];
  $showlocation = $row2['ShowLocation'];
  $profilePic = $row2['ProfilePic'];
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
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">CANTIO</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
          <div>
            <form method="post" action="user_logout.php">
              <button type="submit" name="logout">Log Out</button>
            </form>
          </div>
        </ul>
      </div>
    </div>
  </nav>

  <div class="card container-fluid w-75 mt-5">
    <h1><?php echo $username; ?></h1>
    <?php 
      $query3 = "SELECT * FROM EventOrganizer WHERE UserID LIKE $user_id";
      $result3 = $pdo->query($query3);

      if ($row3 = $result3->fetch()) {
        echo <<<_END
        <h1>Event Organizer</h1>

        _END;


        
      }

    ?>
    <section>
      <img src="<?php echo $profilePic; ?>" style="width: 250px" alt="Profile Image" class="profile-image">
      <h2><?php echo $fullname; ?></h2>
      <p>Musician | Music Enthusiast</p>
    </section>
    <section>
      <h2>About Me</h2>
      <p>Description:
        <?php echo $description; ?>
      </p>
      <p>Gender:
        <?php echo $gender; ?>
      </p>
      <p>Birthday:
        <?php echo $birthday; ?>
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
        $query3 = "SELECT * FROM SocialMediaHandles WHERE ProfileID LIKE 
        (SELECT ProfileID FROM Profile WHERE UserID LIKE $user_id)";
        $result = $pdo->query($query3);

        while ($row = $result->fetch()) {
          $handle_label = $row['Platform'];
          $handle = $row['Handle'];
          $url = $row['URL'];

          echo ' ' . $handle_label . ' ' . $handle . ' ' . $url . '<br>';
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