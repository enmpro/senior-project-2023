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

#rejected requested's id
$request_id = $_GET['id'];

#status is changed to "rejected"
$conn->query("UPDATE FriendRequest SET status = 'rejected' WHERE id = $request_id");

#redirects to homepage
header("Location: homepage_page.html");
exit();
?>
