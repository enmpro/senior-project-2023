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

$sql = "SELECT ProfilePic FROM Profile WHERE UserID = :userid";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userid', $userID, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $profilePicture = $result['ProfilePic'];
    echo $profilePicture;


    $imageData = base64_encode($profilePicture);
    $imageSrc = 'data:image/jpeg;base64,' . $imageData; // Adjust the image type accordingly

        echo "<img src='$imageSrc' alt='Image'>";

} else {
    $profilePicture = '';
}

header("Content-Type: image"); 

?>