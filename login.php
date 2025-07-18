<?php
include "db.php";  // database connection
session_start();   // start session
$loginError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    // If user found
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['name']; // store user's name
        header("Location: dashboard.php");  
        exit();
    } else {
        $loginError = "Invalid email or password!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Interview Prep</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
        }
        body {
            background-color: #0a0a0a;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(to bottom right, #0f2027, #203a43, #2c5364);
        }
        .container {
            background-color: #121212;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
            width: 350px;
            text-align: center;
        }
        .container h2 {
            color: #00e0ff;
            margin-bottom: 20px;
        }
        input[type="email"],
        .password-wrapper {
            width: 100%;
            margin: 10px 0;
            position: relative;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background-color: #1e1e1e;
            color: white;
            font-size: 16px;
        }
        .password-wrapper input {
            padding-right: 40px;
        }
        .password-wrapper i {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }
        input[type="submit"] {
            background-color: #00e0ff;
            color: #0a0a0a;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #00c4e4;
        }
        .error {
            color: #ff4d4d;
            margin-top: 15px;
            font-size: 14px;
        }
        .footer {
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }
        .links {
            margin-top: 15px;
        }
        .links a {
            display: block;
            color: #00e0ff;
            text-decoration: underline;
            margin: 6px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to Interview Assistant</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i onclick="togglePassword()" id="eyeIcon">👁️</i>
            </div>
            <input type="submit" value="Login">
        </form>
        <?php
        if ($loginError != "") {
            echo "<div class='error'>$loginError</div>";
        }
        ?>
        <div class="links">
            <a href="change_password.php">Change Password</a>
            <a href="register.php">Register</a>
        </div>
        <div class="footer">
            © <?php echo date("Y"); ?> Interview Platform
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const icon = document.getElementById("eyeIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.textContent = "🙈";
            } else {
                passwordField.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>
</body>
</html>
