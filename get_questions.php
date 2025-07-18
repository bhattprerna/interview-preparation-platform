<?php
header('Content-Type: application/json'); // Ensure JSON response
$conn = new mysqli("localhost", "root", "", "interview_prep");
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}
if (isset($_GET['role'])) {
    $role = $conn->real_escape_string($_GET['role']);
    
    $sql = "SELECT question FROM questions WHERE LOWER(job_role) = LOWER('$role')";
    $result = $conn->query($sql);

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row['question'];
    }
    echo json_encode($questions);
} else {
    echo json_encode(["error" => "No role specified"]);
}
$conn->close();
?>
<?php

