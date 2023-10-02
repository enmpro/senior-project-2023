<?php
#I certify that this submission is my own original work, Enmanuel Proano
require_once 'login.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}


echo <<< _END
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Main</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="preloader-wrapper">
        <div class="spinner-border text-primary">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container-fluid p-2">
        <header>
            <p class="display-1 text-center">
                Login
            </p>
        </header>
    </div>
    <div class="container w-50 card mt-3 login-box">
        <form action="login.php" method="post">
            <div class="mb-3 mt-3 form-floating">
                <input class="form-control input-box" type="text" name="username" placeholder="Enter Username" />
                <label for="username">
                    Username
                </label>
            </div>
            <div class="mb-3 form-floating">
                <input class="form-control input-box" type="password" minlength="8" name="username" placeholder="Enter Password" />
                <label for="password">
                    Password
                </label>
            </div>
            <div class="form-check mb-3">
                <label class="form-check-label">
                    <input class="form-check-input checkbox-box" type="checkbox" name="remember"> Remember me
                </label>
            </div>
            <div class="text-center">
                <input class="my-3 btn btn-primary login-btn" type="submit" value="Login">
            </div>
        </form>
    </div>
    <div class="container text-center mt-5">
        <p>If you are new...</p>
        <a class="btn btn-primary submit-btn" href="/signup.html">Sign Up</a>
    </div>
    <script src="login.js"></script>
</body>

</html>

_END;

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $username = trim($username);
    $username = stripslashes($username);
    $password = $_POST['password'];
    $password = trim($password);
    $password = stripslashes($password);

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = :username");
    $result = $stmt->execute([':username' => $username]);

    $result_row = $stmt->fetch();

    $hash = $result_row['Password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM Users WHERE Username = ? AND Password = ?');
    $stmt->execute(array($username, password_verify($password, $hash)));

    if (password_verify($password, $hash)) {
         // The user is authenticated
         $_SESSION['user_id'] = $result_row['Username'];
         
         header('Location: main_menu.html');

         exit;
       } else {
         // The user is not authenticated
         echo 
         <<<_END
             <script>
                 alert("Invalid username or password");
             </script>
         _END;
       }
    
}

?>