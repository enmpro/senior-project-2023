<?php
require_once 'logindb.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#rejected requested's id
$request_id = $_GET['id'];

#status is changed to "rejected"
$conn->query("UPDATE friend_requests SET status = 'rejected' WHERE id = $request_id");

#database connection is closed
$conn->close();

#redirects to homepage
header("Location: homepage_page.html");
exit();
?>
