<?php
include 'db.php';
$jobRole = $_GET['job_role'] ?? '';

if (!$jobRole) {
    echo json_encode(['question' => null]);
    exit;
}
$stmt = $conn->prepare("SELECT question FROM questions WHERE job_role = ? AND type = 'mock' ORDER BY RAND() LIMIT 1");
$stmt->bind_param("s", $jobRole);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['question' => $row['question']]);
} else {
    echo json_encode(['question' => null]);
}
$stmt->close();
$conn->close();
