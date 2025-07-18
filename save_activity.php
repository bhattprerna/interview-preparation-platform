<?php
include "db.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity'])) {
    $activity = $conn->real_escape_string($_POST['activity']);
    $user_id = 1; // static user id for now. You can use $_SESSION['user_id'] later
    $sql = "INSERT INTO usage_history (user_id, activity) VALUES ($user_id, '$activity')";
    $conn->query($sql);
}
?>
