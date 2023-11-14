<?php
require_once 'logindb.php';


try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


$flag = false;

$falseCounter = 0;

function test_userinput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {



    //username check
    $usnam = test_userinput($_POST["Username"]);
    $username_regex = "/[^a-zA-Z0-9]/";

    if (preg_match($username_regex, $usnam)) {
        $falseCounter++;
    } else if ($usnam == "") {
        $falseCounter++;
    }

    $fn = test_userinput($_POST["FirstName"]);
    $fl_regex = "/[^a-zA-Z]/";


    if (preg_match($fl_regex, $fn)) {
        $falseCounter++;
    } else if ($fn == "") {
        $falseCounter++;
    }

    // last name check
    $ln = test_userinput($_POST["LastName"]);


    if (preg_match($fl_regex, $ln)) {
        $falseCounter++;
    } else if ($ln == "") {
        $falseCounter++;
    }

    //email check
    $em = test_userinput($_POST["Email"]);


    if ($em == "") {
        $falseCounter++;
    }

    $emPw = test_userinput($_POST["Password"]);
    $confirmPassword = test_userinput($_POST["repword"]);


    $capitalRegex = "/[A-Z]/";
    $numberRegex = "/\d/";


    if ($emPw == "" || $confirmPassword == "") {
        $falseCounter++;
    } else if ($emPw != $confirmPassword) {
        $falseCounter++;

    }

    // this checks if the password has a capital and number
    // kept flagging the password wrong because it didnt include capital or number
    // dont uncomment this just yet

    /*
    else if (!preg_match($capitalRegex, $emPw) || !preg_match($capitalRegex, $confirmPassword)) {
        $falseCounter++;
    } else if (!preg_match($numberRegex, $emPw) || !preg_match($numberRegex, $confirmPassword)) {
        $falseCounter++;
    }
    */



    if ($falseCounter > 0) {
        return false;
    }



}

while (isset($_POST['FirstName'])) {

    if (isset($_POST['Username'])) {
        $un_temp = $_POST['Username'];
        $sql_check = "SELECT * FROM User WHERE Username = '$un_temp'";
        $result = $pdo->query($sql_check);

        if ($result->rowCount()) {
            echo <<<_END
                    <script>
                        alert("Username taken");
                    </script>
                _END;

            break;
        }
    }



    if (
        isset($_POST['Username']) && isset($_POST['FirstName'])
        && isset($_POST['LastName']) && isset($_POST['Email']) && isset($_POST['Password'])
        && isset($_POST['repword']) && isset($_POST['Zip'])
        && isset($_POST['gender']) && isset($_POST['event-yesno'])
    ) {
        $username = $_POST['Username'];
        $username = trim($username);
        $username = stripslashes($username);

        $firstname = $_POST['FirstName'];
        $firstname = trim($firstname);
        $firstname = stripslashes($firstname);

        $lastname = $_POST['LastName'];
        $lastname = trim($lastname);
        $lastname = stripslashes($lastname);

        $email = $_POST['Email'];
        $email = trim($email);
        $email = stripslashes($email);

        $password = $_POST['repword'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $zip = $_POST['Zip'];
        $zip = trim($zip);
        $zip = stripslashes($zip);

        $gender = $_POST['gender'];

        $eventyn = $_POST['event-yesno'];

        $birthday = $_POST['birthday'];

        if (isset($_POST['description'])) {
            $description = $_POST['description'];
        } else {
            $description = '';
        }


        if (isset($_POST['submit'])) {
            $targetDirectory = "userphoto/"; // Directory to store profile pictures

            $randomFileName = uniqid();
            $targetPhotoFile = $targetDirectory . $randomFileName . '_' . basename($_FILES['userphoto']['name']);
            echo <<<_END
                    <script>
                    alert("$targetPhotoFile");
                        
                    </script>
                    _END;

            if (move_uploaded_file($_FILES['userphoto']['tmp_name'], $targetPhotoFile)) {
                echo <<<_END
                    <script>
                        alert("Photo added");
                        
                    </script>
                    _END;
                break;
            } else {
                echo <<<_END
                    <script>
                        alert("Photo not added");
                        
                    </script>
                    _END;
                break;
            }
        } else {
            echo <<<_END
                    <script>
                    alert("not wokring");
                        
                        
                    </script>
                    _END;
            $targetPhotoFile = '';
            break;
        }




        if (isset($_POST['location-chk'])) {
            $locationChk = $_POST['location-chk'];
        } else {
            $locationChk = '';
        }

        if (isset($_POST['age-chk'])) {
            $ageChk = $_POST['age-chk'];
        } else {
            $ageChk = '';
        }

        if (isset($_POST['gender-chk'])) {
            $genderChk = $_POST['gender-chk'];
        } else {
            $genderChk = '';
        }

        // social medias
        if (isset($_POST['facebook'])) {
            $facebook = $_POST['facebook'];
        } else {
            $facebook = '';
        }
        if (isset($_POST['twitter'])) {
            $twitter = $_POST['twitter'];
        } else {
            $twitter = '';
        }
        if (isset($_POST['instagram'])) {
            $instagram = $_POST['instagram'];
        } else {
            $instagram = '';
            echo <<<_END
                    <script>
                    alert("insta");
                        
                        
                    </script>
                    _END;
        }

        if (isset($_POST['orgName'])) {
            $orgName = $_POST['orgName'];
        } else {
            $orgName = '';
        }

        if (isset($_POST['orgType'])) {
            $orgType = $_POST['orgType'];
        } else {
            $orgName = '';
        }

        if (isset($_POST['address'])) {
            $address = $_POST['address'];
        } else {
            $address = '';
        }

        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
        } else {
            $phone = '';
        }

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $email = '';
        }

        if (isset($_POST['website'])) {
            $website = $_POST['website'];
        } else {
            $website = '';
        }



        add_user($pdo, $eventyn, $username, $hash, $email, $firstname, $lastname, $gender, $birthday, $zip);
        $newUserID = $pdo->lastInsertId();

        if ($eventyn == 'yes') {
            echo <<<_END
                    <script>
                    alert("event working");
                        
                        
                    </script>
                    _END;


            add_eventcoord($pdo, $orgName, $orgType, $address, $phone, $email, $url, $newUserID);
        }

        echo <<<_END
                    <script>
                    alert("after");
                        
                        
                    </script>
                    _END;

        update_profile($pdo, $newUserID, $description, $targetPhotoFile, $genderChk, $locationChk, $ageChk);


        $newProfileID = $pdo->lastInsertId();

        $handles = [
            ['Platform' => 'Facebook', 'Handle' => 'user123', 'URL' => $facebook],
            ['Platform' => 'Twitter', 'Handle' => 'user456', 'URL' => $twitter],
            ['Platform' => 'Instagram', 'Handle' => 'user789', 'URL' => $instagram],
        ];

        foreach ($handles as $handleData) {

            $platform = $handleData['Platform'];
            $handle = $handleData['Handle'];
            $url = $handleData['URL'];

            update_social($pdo, $newProfileID, $platform, $handle, $url);
        }

        $flag = true;

        if ($flag) {
            echo <<<_END
                    <script>
                        alert("User added");
                        window.location.href = "landing.html";
                    </script>
                _END;
        }

        break;
    }


}

function add_user($pdo, $event, $user_name, $passwd, $email, $fn, $ln, $gend, $birth, $zip)
{
    $sql = "INSERT INTO User (EventYesNo, Username, Password, Email, FirstName, LastName, Gender, Birthday, Zip) 
            VALUES(:event, :username, :password, :email, :firstname, :lastname, :gender, :birthday, :zip)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':event', $event, PDO::PARAM_STR, 2);
    $stmt->bindParam(':username', $user_name, PDO::PARAM_STR, 25);
    $stmt->bindParam(':password', $passwd, PDO::PARAM_STR, 255);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR, 65);
    $stmt->bindParam(':firstname', $fn, PDO::PARAM_STR, 50);
    $stmt->bindParam(':lastname', $ln, PDO::PARAM_STR, 50);
    $stmt->bindParam(':gender', $gend, PDO::PARAM_STR, 25);
    $stmt->bindParam(':birthday', $birth, PDO::PARAM_STR, 50);
    $stmt->bindParam(':zip', $zip, PDO::PARAM_STR, 5);

    $stmt->execute();
}

function update_profile($pdo, $userid, $description, $profilepic, $showgender, $showlocation, $showbirthday)
{
    // $newUserID = $pdo->lastInsertId();
    $sqlProfile = "INSERT INTO Profile (UserID, Description, ProfilePic, ShowGender, ShowLocation, ShowBirthday) 
                   VALUES (:userID, :descr, :profilepic, :showgend, :showloc, :showbirth)";
    $stmtProfile = $pdo->prepare($sqlProfile);

    $stmtProfile->bindParam(':userID', $userid, PDO::PARAM_STR, 10);
    $stmtProfile->bindParam(':descr', $description, PDO::PARAM_STR);
    $stmtProfile->bindParam(':profilepic', $profilepic, PDO::PARAM_STR);
    $stmtProfile->bindParam(':showgend', $showgender, PDO::PARAM_STR, 12);
    $stmtProfile->bindParam(':showloc', $showlocation, PDO::PARAM_STR, 12);
    $stmtProfile->bindParam(':showbirth', $showbirthday, PDO::PARAM_STR, 12);

    $stmtProfile->execute();
}

function update_social($pdo, $profileID, $platform, $handle, $url)
{

    $sqlProfile = "INSERT INTO SocialMediaHandles (ProfileID, Platform, Handle, URL) 
                   VALUES (:profileID, :platform, :handle, :url)";
    $stmtProfile = $pdo->prepare($sqlProfile);

    $stmtProfile->bindParam(':profileID', $profileID, PDO::PARAM_STR, 11);
    $stmtProfile->bindParam(':platform', $platform, PDO::PARAM_STR, 50);
    $stmtProfile->bindParam(':handle', $handle, PDO::PARAM_STR, 100);
    $stmtProfile->bindParam(':url', $url, PDO::PARAM_STR, 255);

    $stmtProfile->execute();
}

function add_eventcoord($pdo, $orgName, $orgType, $address, $phone, $email, $url, $userid)
{

    $sqlProfile = "INSERT INTO EventOrganizer (OrganizerName, OrganizerType, Address, Phone, ContactEmail, WebsiteURL, UserID) 
                   VALUES (:orgName, :orgType, :address, :phone, :email, :url, :userid)";
    $stmtProfile = $pdo->prepare($sqlProfile);

    $stmtProfile->bindParam(':orgName', $orgName, PDO::PARAM_STR, 255);
    $stmtProfile->bindParam(':orgType', $orgType, PDO::PARAM_STR, 255);
    $stmtProfile->bindParam(':address', $address, PDO::PARAM_STR, 255);
    $stmtProfile->bindParam(':phone', $phone, PDO::PARAM_STR, 20);
    $stmtProfile->bindParam(':email', $email, PDO::PARAM_STR, 100);
    $stmtProfile->bindParam(':url', $url, PDO::PARAM_STR, 255);
    $stmtProfile->bindParam(':userid', $userid, PDO::PARAM_STR, 11);

    $stmtProfile->execute();
}


?>