<?php
include "db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}
// Get logged in user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Guest';
// Fetch user data
$sql = "SELECT name, email, profile_img FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found!");
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <style>
    body {
        background-color: #0e1629;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 40px 20px;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }
    .container {
        background: #111d38;
        padding: 30px;
        border-radius: 12px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }
    .profile {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }
    .profile img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #00c6ff;
        margin-right: 20px;
    }
    .profile-info h2 {
        margin: 0;
        font-size: 22px;
    }
    .profile-info p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #a0aec0;
    }
    .section {
        margin-top: 30px;
    }
    .email-box {
        background: #1e293b;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
    }
    .primary-badge {
        background: #4f46e5;
        color: #fff;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 5px;
        margin-left: 10px;
    }
    .remove-link {
        color: #ff4d4d;
        font-size: 14px;
        position: absolute;
        right: 15px;
        top: 15px;
        text-decoration: none;
    }
    .add-email {
        display: inline-block;
        margin-top: 10px;
        color: #00c6ff;
        text-decoration: none;
        font-size: 14px;
    }
    h1 {
        margin-top: 0;
    }
    .back-button {
    display: inline-block;
    margin-bottom: 20px;
    color: #00c6ff;
    text-decoration: none;
    font-size: 14px;
}
.back-button:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>
<div class="container">
 <a href="profile.php" class="back-button">← Back</a>
 <h1>Account</h1>
 <p>Manage your account information</p>
    <div class="profile">
        <img src="<?php echo htmlspecialchars($user['profile_img']); ?>" alt="Profile Picture">
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        </div>
    </div>
    <div class="section">
        <h3>Email Address</h3>
        <div class="email-box">
            <?php echo htmlspecialchars($user['email']); ?>
            <span class="primary-badge">Primary</span>
            <a href="remove_email.php" class="remove-link">Remove</a>
        </div>
        <a href="add_email.php" class="add-email">+ Add a new email address</a>
    </div>
</div>
</body>
</html>
