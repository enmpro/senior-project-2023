<?php
// Fetch and display friend requests from the database
$result = $conn->query("SELECT * FROM friend_requests WHERE receiver_id = $sender_user_id AND status = 'pending'");

while ($row = $result->fetch_assoc()) {
    echo "<li>{$row['sender_id']} wants to be your friend! 
          <a href='accept_request.php?id={$row['id']}'>Accept</a> 
          <a href='reject_request.php?id={$row['id']}'>Reject</a></li>";
}
?>
