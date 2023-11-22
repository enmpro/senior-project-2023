<?php
require_once 'logindb.php';


try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

$eventID = $_POST['id'];

function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = test_userinput($_POST['eventName']);
    $eventDesc = test_userinput($_POST['eventDesc']);

   

    if (isset($_POST['submit'])) {

        $oldPhotoQuery = "SELECT EventPhoto FROM Event WHERE EventID = :eventID";
        $oldPhotoStmt = $pdo->prepare($oldPhotoQuery);
        $oldPhotoStmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
        $oldPhotoStmt->execute();
        $oldPhoto = $oldPhotoStmt->fetchColumn();

        echo <<<_END
                    <script>
                        alert("$oldPhoto");
                        
                    </script>
                    _END;

        if (!empty($oldPhoto)) {
            // Construct the path to the old photo file (adjust the path as needed)
            $oldPhotoPath = "eventphoto/$oldPhoto";

            echo <<<_END
                    <script>
                        alert("$oldPhotoPath");
                        
                    </script>
                    _END;
            
            // Check if the file exists before attempting to delete
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath); // Delete the old photo file
            }
        }
        

        $targetDirectory = "eventphoto/"; // Directory to store profile pictures

        $randomFileName = uniqid();
        $targetPhotoFile = $targetDirectory . $randomFileName . '_' . basename($_FILES['eventPhoto']['name']);
        echo <<<_END
                    <script>
                    alert("$targetPhotoFile");
                    alert("$eventName");     
                    alert("$eventDesc");                       
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

    $query = "UPDATE Event 
    SET EventName = :eventName,
         EventDesc = :eventDesc,
         EventPhoto = :eventPhoto
    WHERE EventID = $eventID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':eventName', $eventName, PDO::PARAM_STR, 255);
    $stmt->bindParam(':eventDesc', $eventDesc, PDO::PARAM_STR, 255);
    $stmt->bindParam(':eventPhoto', $targetPhotoFile, PDO::PARAM_STR, 255);

    $stmt->execute();
}



header('Location: event_coord.php');

?>