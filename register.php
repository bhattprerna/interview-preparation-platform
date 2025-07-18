<?php
include "db.php";
$registerError = "";
$registerSuccess = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        $registerError = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $registerSuccess = "Registration successful! You can now login.";
        } else {
            $registerError = "Something went wrong. Try again.";
        }
        $stmt->close();
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            background: #102c3d;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-box {
            background-color: #111;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px #00e0ff88;
            width: 350px;
        }
        .register-box h2 {
            text-align: center;
            color: #00e0ff;
            margin-bottom: 20px;
        }
        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: none;
            border-radius: 10px;
            background: #222;
            color: #fff;
            font-size: 16px;
        }
        .register-box input[type="submit"] {
            background-color: #00e0ff;
            color: #000;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: #ff4d4d;
        }
        .success {
            color: #00ffcc;
        }
        .show-password {
            position: relative;
        }
        .show-password i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
        }
        .login-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #00ffff;
            text-decoration: underline;
        }
        .login-link:hover {
            color: #00ccff;
        }
    </style>
</head>
<body>
<div class="register-box">
    <h2>Register</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <div class="show-password">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i onclick="togglePassword()" class="fa fa-eye" id="toggleIcon"></i>
        </div>
        <input type="submit" value="Register">
    </form>
    <?php
if (isset($_POST['register'])) {
    // connect database
    $conn = mysqli_connect('localhost', 'root', '', 'your_db');
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
    echo "Registration successful!";
}
 if ($registerError): ?>
    <p class="message"><?php echo $registerError; ?></p>
<?php endif; ?>
<?php if ($registerSuccess): ?>
    <p class="message success"><?php echo $registerSuccess; ?></p>
    <a href="login.php" class="login-link">Back to Login</a>
<?php endif; 
?>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>
