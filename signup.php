<?php
#I certify that this submission is my own original work, Enmanuel Proano
require_once 'login.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

echo <<<_END
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>sign up</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="signup.css">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <a class="m-3 btn btn-primary back-btn" href="login.html">Go Back</a>

        <div class="preloader-wrapper">
            <div class="spinner-border text-primary">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="container-fluid p-2">
            <header>
                <p class="display-1 text-center">
                    Signup
                </p>
            </header>
        </div>

        <div class="container w-50 card mt-3 signup-box">
            <form action="signup.php" method="post">
                <div class="mb-3 mt-3 form-floating">
                    <input class="form-control input-box" type="text" name="username" placeholder="Enter Username" required/>
                    <label for="username">
                        Username
                    </label>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input class="form-control input-box" type="text" name="fname" placeholder="Enter Username" required/>
                    <label for="fname">
                        First Name
                    </label>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input class="form-control input-box" type="text" name="lname" placeholder="Enter Username" required/>
                    <label for="lname">
                        Last Name
                    </label>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input class="form-control input-box" type="email" name="email" placeholder="Enter Username" required/>
                    <label for="email">
                        Email
                    </label>
                </div>
                <div class="mb-3 form-floating">
                    <input class="form-control input-box" minlength="8" type="password" name="pword" placeholder="Enter Password" required/>
                    <label for="password">
                        Password
                    </label>
                </div>
                <div class="mb-3 form-floating">
                    <input class="form-control input-box" minlength="8" type="password" name="repword" placeholder="Enter Password" required/>
                    <label for="repword">
                        Re-enter Password
                    </label>
                </div>
                <div class="text-center">
                    <input class="my-3 btn btn-primary signup-btn" type="submit" value="Sign up">
                </div>
            </form>
    <script src="signup.js"></script>
    </body>
</html>

_END;

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
    $usnam = test_userinput($_POST["username"]);
    $username_regex = "/[^a-zA-Z0-9]/";

    if (preg_match($username_regex, $usnam)) {
        $falseCounter++;
    } else if ($usnam == "") {
        $falseCounter++;
    }

    $fn = test_userinput($_POST["fname"]);
    $fl_regex = "/[^a-zA-Z]/";

    if (preg_match($fl_regex, $fn)) {
        $falseCounter++;
    } else if ($fn == "") {
        $falseCounter++;
    } 

    // last name check
    $ln = test_userinput($_POST["lname"]);

    if (preg_match($fl_regex, $ln)) {
        $falseCounter++;
    } else if ($ln == "") {
        $falseCounter++;
    } 

    //email check
    $em = test_userinput($_POST["email"]);
    if ($em == "") {
        $falseCounter++;
    }
    
    $password = test_userinput($_POST["password"]);
    $confirmPassword = test_userinput($_POST["repword"]);

    $capitalRegex = "/[A-Z]/";
    $numberRegex = "/\d/";

    if ($password == "" || $confirmPassword == "") {
        $falseCounter++;
    } else if ($password != $confirmPassword) {
        $falseCounter++;
        
    } else if (!preg_match($capitalRegex, $password) || !preg_match($capitalRegex, $confirmPassword)) {
        $falseCounter++;
    } else if (!preg_match($numberRegex, $password) || !preg_match($numberRegex, $confirmPassword)) {
        $falseCounter++;
    }

    if ($falseCounter > 0) {
        return false;
    } 

}

while (isset($_POST['fname'])) {

    if (isset($_POST['username'])) {
        $un_temp = $_POST['username'];
        $sql_check = "SELECT * FROM Users WHERE Username = '$un_temp'";
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
        isset($_POST['username']) && isset($_POST['fname'])
        && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['password']) 
        && isset($_POST['repword'])
    ) {
        $username = $_POST['username'];
        $username = trim($username);
        $username = stripslashes($username);

        $firstname = $_POST['fname'];
        $firstname = trim($firstname);
        $firstname = stripslashes($firstname);

        $lastname = $_POST['lname'];
        $lastname = trim($lastname);
        $lastname = stripslashes($lastname);
        
        $email = $_POST['email'];
        $email = trim($email);
        $email = stripslashes($email);

        $password = $_POST['repword'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        add_user($pdo, $username, $firstname, $lastname, $email, $hash);
        $flag = true;

        if ($flag) {
            echo <<<_END
                    <script>
                        function pageRedirect(){
                            var delay = 3000; // time in milliseconds
                            setTimeout(function(){
                                window.location.href = "main.php";
                            },delay);
                        
                        }

                        alert("User added");
                        pageRedirect();
                    </script>
                _END;
        }

        break;
    }


}

function add_user($pdo, $user_name, $fn, $ln, $email, $passwd)
{
    $sql = "INSERT INTO Users(Username, FirstName, LastName, Email, Password) 
            VALUES(:username, :firstname, :lastname, :email, :password)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':username', $user_name, PDO::PARAM_STR, 25);
    $stmt->bindParam(':firstname', $fn, PDO::PARAM_STR, 50);
    $stmt->bindParam(':lastname', $ln, PDO::PARAM_STR, 50);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR, 65);
    $stmt->bindParam(':password', $passwd, PDO::PARAM_STR, 32);

    $stmt->execute();
}

?>