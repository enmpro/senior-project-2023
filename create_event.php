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



function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $eventName = test_userinput($_POST["eventName"]);
    $eventDesc = test_userinput($_POST["eventDesc"]);
    $eventPhoto = test_userinput($_POST["eventPhoto"]);

    $query = "SELECT * FROM EventOrganizer WHERE UserID LIKE $user_id";
    $result = $pdo->query($query);

    if ($row = $result->fetch()) {
        $organizerID = $row["OrganizerID"];
      }


    if (isset($_POST["submit"])) {
        $targetDirectory = "eventphoto/"; // Directory to store profile pictures

        $randomFileName = uniqid();
        $targetPhotoFile = $targetDirectory . $randomFileName . '_' . basename($_FILES['eventPhoto']['name']);
        echo <<<_END
                    <script>
                    alert("$targetPhotoFile");
                        
                    </script>
                    _END;

        if (move_uploaded_file($_FILES['eventPhoto']['tmp_name'], $targetPhotoFile)) {
            echo <<<_END
                    <script>
                        alert("Photo added");
                        
                    </script>
                    _END;
        } else {
            echo <<<_END
                    <script>
                        alert("Photo not added");
                        
                    </script>
                    _END;

            $targetPhotoFile = '';
        }


    }

    add_event($pdo, $eventName, $eventDesc, $eventPhoto, 0, $organizerID);

}


function add_event($pdo, $eventName, $eventDesc, $eventPhoto, $numAttend, $organizerID)
{
    $sql = "INSERT INTO Event (EventName, EventDesc, EventPhoto, UserNumAttend, OrganizerID) 
            VALUES(:event, :eventName, :eventDesc, :eventPhoto, :numAttend, :organizerID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':eventName', $eventName, PDO::PARAM_STR, 255);
    $stmt->bindParam(':eventDesc', $eventDesc, PDO::PARAM_STR, 255);
    $stmt->bindParam(':eventPhoto', $eventPhoto, PDO::PARAM_STR, 255);
    $stmt->bindParam(':numAttend', $numAttend, PDO::PARAM_INT, 11);
    $stmt->bindParam(':organizerID', $organizerID, PDO::PARAM_INT, 11);

    $stmt->execute();
}

?>