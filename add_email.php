<?php
include "db.php";
session_start();
$user_id = 1;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    // Update email
    $sql = "UPDATE users SET email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_email, $user_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Email updated successfully'); window.location.href='account_settings.php';</script>";
    } else {
        echo "<script>alert('Failed to update email'); window.location.href='account_settings.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Email</title>
    <style>
    body {
        background-color: #0e1629;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 20px;
        color: #fff;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }
    a.back-link {
        color: #00c6ff;
        margin-bottom: 20px;
        text-decoration: none;
        display: inline-block;
    }
    .form-container {
        background: #111d38;
        padding: 30px;
        border-radius: 12px;
        width: 400px;
        margin: auto;
        margin-top: 40px;
    }
    h2 {
        margin-top: 0;
    }
    form {
        display: flex;
        flex-direction: column;
    }
    input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: none;
        background: #1e293b;
        color: #fff;
        font-size: 16px;
    }
    .button-group {
        display: flex;
        justify-content: space-between;
    }
    button {
        width: 48%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }
    .save-btn {
        background-color: #00c6ff;
        color: #111d38;
    }
    .save-btn:hover {
        background-color: #00a3cc;
    }
    .cancel-btn {
        background-color: transparent;
        border: 1px solid #fff;
        color: #fff;
    }
    .cancel-btn:hover {
        background-color: #1e293b;
    }
    </style>
</head>
<body>
<a href="account_settings.php" class="back-link">← Back</a>
<div class="form-container">
    <h2>Add Email Address</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter new email" required>
        <div class="button-group">
            <button type="button" class="cancel-btn" onclick="window.location.href='account_settings.php'">Cancel</button>
            <button type="submit" class="save-btn">Continue</button>
        </div>
    </form>
</div>
</body>
</html>
