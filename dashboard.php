<?php
session_start();
include 'navbar.php';
include 'db.php';
$username = $_SESSION['username'] ?? 'Guest';
$stats = [
    'questions_generated' => 0,
    'speech_tests_completed' => 0,
    'resumes_analyzed' => 0,
];
$q1 = $conn->query("SELECT COUNT(*) AS total FROM questions");
$stats['questions_generated'] = $q1->fetch_assoc()['total'] ?? 0;
$q2 = $conn->query("SELECT COUNT(*) AS total FROM sessions");
$stats['speech_tests_completed'] = $q2->fetch_assoc()['total'] ?? 0;
$q3 = $conn->query("SELECT COUNT(*) AS total FROM user_results ");
$stats['resumes_analyzed'] = $q3->fetch_assoc()['total'] ?? 0;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #0d0d0d;
    padding: 20px;
    margin: 0;
    color: #ffffff;
  }
  h1 {
    text-align: left;
    font-size: 32px;
    margin-bottom: 30px;
    color: #00f0ff;
    text-shadow: 0 0 10px #00f0ff;
    margin-left: 20px;
  }
  .carousel-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 2rem;
  color: #00f0ff;
  cursor: pointer;
  z-index: 1;
  padding: 0 15px;
  user-select: none;
  }
  .carousel-arrow.left {
    left: 0;
  }
  .carousel-arrow.right {
    right: 0;
  }
  .carousel-container {
    position: relative;
    max-width: 900px;
    height:120px;
    margin: 0 auto 40px auto;
    overflow: hidden;
    border-radius: 15px;
    border: 2px solid #00f0ff;
    box-shadow: 0 0 15px #00f0ff;
    background-color: #111;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .carousel {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 1s ease-in-out;
  }
  .carousel-item {
    min-width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #00f0ff;
    text-align: center;
    padding: 0 20px;
    box-sizing: border-box;
  }
  .dashboard {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
  }
  .card {
    background: #111111;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 0 15px #00f0ff;
    flex: 1 1 200px;
    max-width: 250px;
    text-align: center;
    transition: transform 0.3s;
  }
  .card:hover {
    transform: scale(1.05);
  }
  .card h3 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #00f0ff;
  }
  .card p {
    font-size: 30px;
    font-weight: bold;
    color: #ffffff;
  }
  </style>
</head>
<body>
<h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
<div class="carousel-container" onmouseover="pauseCarousel()" onmouseout="resumeCarousel()">
  <div class="carousel-arrow left" onclick="prevSlide()">&#10094;</div>
  <div class="carousel" id="tip-carousel">
    <div class="carousel-item">💪 <strong>Motivation:</strong> Believe you can and you're halfway there.</div>
    <div class="carousel-item">💼 <strong>Resume Tip:</strong> Tailor your resume for each job application.</div>
    <div class="carousel-item">🎤 <strong>Speech Tip:</strong> Practice in front of a mirror or record yourself.</div>
    <div class="carousel-item">💪 <strong>Motivation:</strong> Push yourself, because no one else will.</div>
    <div class="carousel-item">💼 <strong>Resume Tip:</strong> Use strong action verbs like “Led” and “Created”.</div>
    <div class="carousel-item">🎤 <strong>Speech Tip:</strong> Pause intentionally — it creates impact.</div>
  </div>
  <div class="carousel-arrow right" onclick="nextSlide()">&#10095;</div>
</div>
<!-- ✅ Stat Cards Section -->
<div class="dashboard">
  <a href="question_generation.php" class="card-link" onclick="saveActivity('Opened Question Generation')">
    <div class="card">
      <h3>Questions Generated</h3>
      <p><?= $stats['questions_generated'] ?></p>
    </div>
  </a>
  <a href="speech_practice.php" class="card-link" onclick="saveActivity('Opened Speech Practice')">
    <div class="card">
      <h3>Speech Tests Completed</h3>
      <p><?= $stats['speech_tests_completed'] ?></p>
    </div>
  </a>
  <a href="resume_index.php" class="card-link" onclick="saveActivity('Opened Resume Analysis')">
    <div class="card">
      <h3>Resumes Analyzed</h3>
      <p><?= $stats['resumes_analyzed'] ?></p>
    </div>
  </a>
</div>
<!-- ✅ Carousel Script -->
<script>
  const carousel = document.getElementById('tip-carousel');
  const items = document.querySelectorAll('.carousel-item');
  let index = 0;
  let interval = setInterval(rotateCarousel, 30000);
  function rotateCarousel() {
    index = (index + 1) % items.length;
    updateCarousel();
  }
  function updateCarousel() {
    carousel.style.transform = `translateX(-${index * 100}%)`;
  }
  function prevSlide() {
    index = (index - 1 + items.length) % items.length;
    updateCarousel();
    resetInterval();
  }
  function nextSlide() {
    index = (index + 1) % items.length;
    updateCarousel();
    resetInterval();
  }
  function pauseCarousel() {
    clearInterval(interval);
  }
  function resumeCarousel() {
    interval = setInterval(rotateCarousel, 10000);
  }
  function resetInterval() {
    clearInterval(interval);
    interval = setInterval(rotateCarousel, 10000);
  }
</script>
</body>
</html>
