<?php
include "db.php";

// Save activity if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activity'])) {
    $user_id = intval($_POST['user_id']); 
    $activity = $conn->real_escape_string($_POST['activity']);
    $sql = "INSERT INTO usage_history (user_id, activity) VALUES ($user_id, '$activity')";
    $conn->query($sql);
}

// Fetch usage history
$history = array();
$sql = "SELECT * FROM usage_history ORDER BY activity_time DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usage History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0e162e;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
        }

        .container {
            width: 600px;
            background: #1c2541;
            padding: 30px;
            border-radius: 10px;
        }

        .history-item {
            background: #3a506b;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        button {
            padding: 10px 15px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0097a7;
        }
        .back-button {
        color: #00BFFF; /* Bright Blue (or pick your favorite shade) */
        font-size: 18px; /* Make it slightly bigger if you want */
        text-decoration: none; /* Remove underline */
        font-weight: bold; /* Optional: make it bold */
}
    </style>
</head>
<body>

<div class="container">
<a href="profile.php" class="back-button">← Back</a>
    <h2>Usage History</h2>

    <!-- Show history -->
    <?php foreach($history as $item): ?>
        <div class="history-item">
            <p><strong>Activity:</strong> <?php echo htmlspecialchars($item['activity']); ?></p>
            <p><strong>Time:</strong> <?php echo $item['activity_time']; ?></p>
        </div>
    <?php endforeach; ?>

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
