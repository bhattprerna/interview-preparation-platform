<?php
include "db.php";
session_start();

// Example: Replace with your session user_id
$user_id = 1;

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Update user email to NULL or empty
    $sql = "UPDATE users SET email = NULL WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Email removed successfully'); window.location.href='account_settings.php';</script>";
    } else {
        echo "<script>alert('Failed to remove email'); window.location.href='account_settings.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
