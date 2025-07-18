<?php
include "db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Guest';
// Handle profile picture update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_img'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if not exists
        } 
    $target_file = $target_dir . basename($_FILES["profile_img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Simple validation (only allow jpg, png, jpeg)
    if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
            // Update database
            $sql = "UPDATE users SET profile_img = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $user_id);
            $stmt->execute();
            $stmt->close(); } }
    }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Module</title>
    <style>
    body {
        background-color: #0e1629;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .profile-card {
        background: #111d38;
        border-radius: 12px;
        padding: 30px;
        width: 400px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
    }
    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #00c6ff;
        cursor: pointer;
        margin-right: 20px;
        transition: transform 0.3s ease;
    }
    .upload-label {
        position: absolute;
        bottom: 0;
        left: 60px; /* adjust based on your image width */
        transform: translateX(-50%);
        background: #00c6ff;
        color: #111d38;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        display: none;
    }
    .profile-img:hover {
    transform: scale(1.05);
    }
    .profile-header:hover .upload-label {
        display: block;
    }
    .profile-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .profile-info h2 {
        margin: 0 0 5px;
        color: #ffffff;
        font-size: 22px;
        text-align: left;
    }
    .profile-info p {
        margin: 0;
        color: #a0aec0;
        font-size: 14px;
        text-align: left;
    }
    .profile-actions h3 {
        color: #ffffff;
        font-size: 18px;
        margin-bottom: 10px;
        text-align: center;
    }
    .action-btn {
        background: #1e293b;
        color: #ffffff;
        width: 100%;
        border: none;
        padding: 12px;
        margin-bottom: 10px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .action-btn:hover {
        background: #2c3e60;
    }
    .logout {
        background: #ff4d4d;
    }
    .logout:hover {
        background: #ff1a1a;
    }
    .icon {
        font-size: 18px;
    }
    input[type="file"] {
        display: none;
    }
    .back-button {
        color: #00BFFF; 
        font-size: 18px; 
        text-decoration: none; 
        font-weight: bold; 
}
</style>
</head>
<body>
<div class="profile-card">
<a href="dashboard.php" class="back-button">← Back</a>
    <div class="profile-header">
        <form id="uploadForm" action="" method="post" enctype="multipart/form-data" style="position: relative;">
            <label for="profile_img">
                <img src="<?php echo htmlspecialchars($user['profile_img']); ?>" alt="Profile Picture" class="profile-img" id="previewImg">
                <div class="upload-label">Change</div>
            </label>
            <input type="file" name="profile_img" id="profile_img" onchange="document.getElementById('uploadForm').submit();">
        </form>
        <!-- Move profile-info INSIDE the profile-header -->
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>
    <div class="profile-actions">
    <h3>Manage Account</h3>
    <form action="account_settings.php" method="get" onsubmit="saveActivity('Opened Account Settings')">
        <button class="action-btn">
            <span>Account Settings</span>
            <span class="icon">⚙️</span>
        </button>
    </form>
    <form action="usage_history.php" method="get" onsubmit="saveActivity('Opened Usage History')">
        <button class="action-btn">
            <span>Usage History</span>
            <span class="icon">🕒</span>
        </button>
    </form>
    <form action="logout.php" method="post" onsubmit="saveActivity('Logged Out')">
        <button class="action-btn logout">
            <span>Log Out</span>
            <span class="icon">🚪</span>
        </button>
    </form>
</div>
</div>
<script>
function saveActivity(activity) {
    fetch('save_activity.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'activity=' + encodeURIComponent(activity)
    });
}
</script>
</body>
</html>
