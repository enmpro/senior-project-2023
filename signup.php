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

        add_user($pdo, $username, $hash, $email, $firstname, $lastname, $zip );
        $flag = true;

        if ($flag) {
            echo <<<_END
                    <script>
                        alert("User added");
                        window.location.href = "login.html";
                    </script>
                _END;
        }

        break;
    }


}

function add_user($pdo, $user_name, $passwd, $email, $fn, $ln, $zip)
{
    $sql = "INSERT INTO Users(Username, Password, Email, FirstName, LastName, Zip) 
            VALUES(:username, :password, :email, :firstname, :lastname, :zip)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':username', $user_name, PDO::PARAM_STR, 25);
    $stmt->bindParam(':password', $passwd, PDO::PARAM_STR, 255);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR, 65);
    $stmt->bindParam(':firstname', $fn, PDO::PARAM_STR, 50);
    $stmt->bindParam(':lastname', $ln, PDO::PARAM_STR, 50);
    $stmt->bindParam(':zip', $zip, PDO::PARAM_STR, 5);


    $stmt->execute();
}


?>