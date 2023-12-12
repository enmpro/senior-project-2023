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

$userOwnID = $_SESSION['user_id'];

$user_id = $_GET['id'];

$query = "SELECT *, FLOOR(TIMESTAMPDIFF(YEAR, Birthday, CURDATE())) as Age FROM User WHERE UserID LIKE $user_id";
$result = $pdo->query($query);

if ($row = $result->fetch()) {
  $username = $row['Username'];
  $fullname = $row['FirstName'] . ' ' . $row['LastName'];
  $gender = $row['Gender'];
  $zip = $row['Zip'];
  $birthday = $row['Age'];
  $userEmail = $row['Email'];
}

$query2 = "SELECT * FROM Profile WHERE UserID LIKE $user_id";
$result2 = $pdo->query($query2);

if ($row2 = $result2->fetch()) {
  $description = $row2['Description'];
  $showgender = $row2['ShowGender'];
  $showlocation = $row2['ShowLocation'];
  $profilePic = $row2['ProfilePic'];
}




$query3 = "SELECT * FROM EventOrganizer WHERE UserID LIKE $userOwnID";
$result3 = $pdo->query($query3);

if ($row3 = $result3->fetch()) {
  $organizerBoolNav = true;
} else {
  $organizerBoolNav = false;
}

$queryCheck = "SELECT * FROM EventOrganizer WHERE UserID LIKE $user_id";
$resultCheck = $pdo->query($queryCheck);

if ($rowCheck = $resultCheck->fetch()) {
  $organizerProfBool = true;
} else {
  $organizerProfBool = false;
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://kit.fontawesome.com/fe58b05d68.js" crossorigin="anonymous"></script>
  <!-- Latest compiled and minified CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="profile.css">
</head>

<style>
  .scrollspy-event {
    position: relative;
    max-height: 600px;
    overflow: auto;
  }

  .event-item {
    background-color: #e0e0e0;
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 10px;
  }

  .event-image {
    max-width: 100%;
    height: 120px;
    border-radius: 5px;
    overflow: hidden;
    object-fit: cover;
  }

  .event-details {
    margin-top: 10px;
  }

  .attendees {
    color: #666;
  }
</style>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">CANTIO</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link" href="/spotify/explore_page.php">Explore Music</a>
                    </li>
          <li class="nav-item">
            <a class="nav-link" href="community.php">Community</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user_event.php">Event</a>
          </li>
          <?php
          if ($organizerBoolNav) {
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


  <div class="container mb-5">
    <div class="row">
      <div class="col-md-4">
        <div class="profile-container">
          <div class="text-center">
            <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-picture">
          </div>
          <div class="user-info">
            <h2><?php echo $fullname; ?> | <?php echo $username; ?></h2>
            <?php
            $query4 = "SELECT * FROM EventOrganizer WHERE UserID LIKE $user_id";
            $result4 = $pdo->query($query4);
            if ($row4 = $result4->fetch()) {
              $organizerName = $row4['OrganizerName'];
              $organizerType = $row4['OrganizerType'];
              $organizerAddress = $row4['Address'];
              $organizerPhone = $row4['Phone'];
              $organizerEmail = $row4['ContactEmail'];
              $organizerUrl = $row4['WebsiteURL'];
              echo <<<_END
            <h4><i>Event Organizer</i></h4>
          _END;
            }
            ?>
            <div class="mt-3">
              <p><i class="fa-regular fa-envelope"></i> <?php echo $userEmail; ?></p>
            </div>
            <div>
              <p><i class="fa-solid fa-globe"></i> <?php echo $zip; ?></p>
            </div>
          </div>
          <?php
          if ($organizerProfBool) {
            ?>
            <div class="text-center">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#organizerModal">Organizer
                Information</button>
            </div>
            <?php
          }
          ?>


          <hr>

          <div class="additional-details text-center">
            <h3>Additional Details</h3>
            <div class="mt-3">
              <p>p><i class="fa-solid fa-cake-candles"></i> <?php echo $birthday; ?></p>
            </div>
            <div>
              <p><strong>Description</strong></p>
              <p><?php echo $description; ?></p>
            </div>
            <div>
              <p><strong>Social Media</strong></p>
              <?php
              $socialQuery = "SELECT * FROM SocialMediaHandles WHERE ProfileID = (Select ProfileID from Profile WHERE UserID = $user_id)";
              $socialResult = $pdo->query($socialQuery);
              $socialCount = 0;
              foreach ($socialResult as $row) {
                $facebook = $row['Platform'];
                echo $facebook . "<br>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="card shadow-sm" style="max-height: 600px;">
          <div class="card-header text-center">
            <p class="fs-2">Friends List</p>
          </div>
          <div class="card-body">

            <div class="card-text">
              <p>No Friends</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div style="margin: 50px auto;">
          <div class="card shadow-sm">
            <div class="card-header text-center">
              <p class="fs-2">Events</p>
            </div>
            <div class="card-body">
              <div class="list-group">
                <div class=" scrollspy-event list-group" data-bs-spy="scroll">
                  <?php
                  $rsvpQuery = "SELECT * FROM UserRSVP WHERE UserID LIKE $user_id";
                  $rsvpResult = $pdo->query($rsvpQuery);
                  $count = 0;
                  foreach ($rsvpResult as $row) {
                    $rsvp_eventID = $row['EventID'];
                    $rsvpStatus = $row['RSVPStatus'];

                    $count = $count + 1;
                    $eventRsvp = "SELECT * FROM Event WHERE EventID LIKE $rsvp_eventID";
                    $eventRsvpResult = $pdo->query($eventRsvp);

                    if ($eventResult = $eventRsvpResult->fetch()) {
                      $eventName = $eventResult['EventName'];
                      $eventArtist = $eventResult['EventArtist'];
                      $eventDesc = $eventResult['EventDesc'];
                      $eventPhoto = $eventResult['EventPhoto'];
                      $eventNum = $eventResult['UserNumAttend'];
                      $eventDate = $eventResult['EventDateTime'];
                    }

                    if ($rsvpStatus == 'Attending') {
                      ?>


                      <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                          <h4 class="mb-3"><?php echo $eventName; ?></h4>
                          <small><?php echo $eventDate; ?></small>
                        </div>
                        <img class="event-image mb-3" src="<?php echo $eventPhoto; ?>" alt="Event Image">
                        <p class="fs-4"><?php echo $eventArtist; ?></p>
                        <p class="mb-1"><?php echo $eventDesc; ?></p>
                        <p class="attendees"><?php echo $eventNum; ?>     <?php
                                if ($eventNum < 2) {
                                  echo "person";
                                } else {
                                  echo "people";
                                } ?> attending</p>
                      </a>

                      <?php
                    }

                    if ($rsvpStatus == 'Maybe') {
                      ?>

                      <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                          <h4 class="mb-3"><?php echo $eventName; ?></h4>
                          <small><?php echo $eventDate; ?></small>
                        </div>
                        <img class="event-image mb-3" src="<?php echo $eventPhoto; ?>" alt="Event Image">
                        <p class="fs-4"><?php echo $eventArtist; ?></p>
                        <p class="mb-1"><?php echo $eventDesc; ?></p>
                        <p class="attendees">Possibly interested</p>
                      </a>
                      <?php
                    }
                  }

                  if ($count == 0) {

                    ?>

                    <div class="text-center m-auto">
                      <h1>No Events Here...</h1>
                    </div>

                    <?php
                  }
                  ?>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="card mt-3">
          <div class="card-header text-center">
            <p class="fs-2">Music</p>
          </div>
          <div class="card-body">
            <h5 class="card-title">Liked Music</h5>
            <h5 class="card-title">Liked Artists</h5>
          </div>
        </div>

      </div>
    </div>
  </div>



  <div class="modal fade" id="organizerModal" tabindex="-1" aria-labelledby="organizerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="organizerModalLabel">Event Organizer Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div>
            <p><strong>Organization Name</strong></p>
            <p><?php echo $organizerName; ?></p>
          </div>
          <div>
            <p><strong>Type</strong></p>
            <p><?php echo $organizerType; ?></p>
          </div>
          <div>
            <p><strong>Address</strong></p>
            <p><?php echo $organizerAddress; ?></p>
          </div>
          <div>
            <p><strong>Phone</strong></p>
            <p><?php echo $organizerPhone; ?></p>
          </div>
          <div>
            <p><strong>Email</strong></p>
            <p><?php echo $organizerEmail; ?></p>
          </div>
          <div>
            <p><strong>Website</strong></p>
            <p><a href="<?php echo $organizerUrl; ?>"><?php echo $organizerUrl; ?></a></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn contact-btn">
            Contact Organizer
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Latest compiled JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>