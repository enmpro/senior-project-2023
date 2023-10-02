<?php
#I certify that this submission is my own original work, Enmanuel Proano
require_once 'logindb.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

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
                function pageRedirect(){
                    var delay = 3000; // time in milliseconds
                    setTimeout(function(){
                        window.location.href = "login.html";
                    },delay);
                
                }

                 alert("Invalid username or password");
                 pageRedirect();
             </script>
         _END;
       }
    
}

?>