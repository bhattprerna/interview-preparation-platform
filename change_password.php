<?php
session_start();
include "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];  

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $newPassword, $email);
    if ($stmt->execute()) {
        echo "<script>alert('Password changed successfully!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Failed to change password.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body {
            background-color: #112c36;
            font-family: Arial, sans-serif;
            color: white;
        }
        .container {
            width: 350px;
            margin: 100px auto;
            padding: 30px;
            background-color: #111;
            border-radius: 20px;
            box-shadow: 0 0 20px cyan;
            text-align: center;
        }
        h2 {
            color: cyan;
        }
        input[type="email"],
        input[type="password"] {
            width: 85%;
            padding: 10px;
            margin: 10px auto;
            display: block;
            border-radius: 10px;
            border: none;
            background-color: #222;
            color: white;
        }
        .input-group {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 35px;
            top: 55%;
            transform: translateY(-50%);
            cursor: pointer;
            color: white;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: cyan;
            color: black;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
        }
        a {
            color: cyan;
            display: block;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Change Password</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Your Email" required><br>
        <div class="input-group">
            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div><br>
        <input type="submit" value="Change Password">
        <a href="login.php">← Back to Login</a>
    </form>
</div>
<script>
function togglePassword() {
    var passwordInput = document.getElementById("new_password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}
</script>
</body>
</html>
