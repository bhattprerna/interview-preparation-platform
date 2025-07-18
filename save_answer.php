<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "interview_prep";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answer = $_POST['answer'];
    
    $sql = "INSERT INTO answers (response) VALUES ('$answer')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Answer saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
