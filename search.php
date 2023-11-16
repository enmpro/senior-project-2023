<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>

<h1>Search Results</h1>

<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search_term = $_POST['search_term'];

    include 'logindb.php';
?>

</body>
</html>