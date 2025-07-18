<?php
// Database connection details
$servername = "localhost";
$db_username = "root"; // default for XAMPP
$db_password = "";     // default for XAMPP (no password)
$dbname = "interview_prep"; // your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
  die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Extract data
$user = $data['username'];
$total_marks = $data['total_marks'];
$correct_answers = $data['correct_answers'];
$total_questions = $data['total_questions'];
$percentage = $data['percentage'];

// Insert into database
$sql = "INSERT INTO user_results (username, total_marks, correct_answers, total_questions, percentage) 
        VALUES ('$user', $total_marks, $correct_answers, $total_questions, $percentage)";

if ($conn->query($sql) === TRUE) {
  echo json_encode(["message" => "Results saved successfully"]);
} else {
  echo json_encode(["message" => "Error: " . $conn->error]);
}

// Close connection
$conn->close();
?>
