<?php
include 'navbar.php';
include 'db.php';

$username = "test_user"; // replace later with $_SESSION['username'] if login system exists

// Fetch the latest result for this user
$sql = "SELECT * FROM user_results WHERE username = ? ORDER BY test_date DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$totalMarks = $userData['total_marks'] ?? 0;
$correctAnswers = $userData['correct_answers'] ?? 0;
$totalQuestions = $userData['total_questions'] ?? 1;
$percentage = round(($correctAnswers / $totalQuestions) * 100);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test Results</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
  body {
    background-color: #0d0d0d;
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
    padding: 40px;
  }
  h1 {
    text-align: center;
    margin-bottom: 30px;
  }
  .container {
    display: flex;
    flex-direction: row; /* <-- changed from column */
    align-items: center;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap; /* responsive fallback */
  }
  .card {
    background: #222;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 10px cyan;
    text-align: center;
    width: 300px;
  }
  .progress {
    background: #111;
    border-radius: 12px;
    overflow: hidden;
    height: 25px;
    margin: 20px 0;
  }
  .progress-bar {
    height: 100%;
    background: cyan;
    color: #000;
    text-align: center;
    line-height: 25px;
    font-weight: bold;
    transition: width 0.5s;
  }
  .chart-container {
    width: 300px;
    height: 300px;
  }
  button {
    background: cyan;
    color: #000;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
  }
</style>

</head>
<body>

  <h1>🎯 Your Performance</h1>

  <div class="container">
    <div class="card">
      <p><strong>Marks:</strong> <?= $totalMarks ?></p>
      <p><strong>Correct Answers:</strong> <?= $correctAnswers ?>/<?= $totalQuestions ?></p>
      <p><strong>Percentage:</strong> <?= $percentage ?>%</p>

      <div class="progress">
        <div class="progress-bar" id="progressBar"><?= $percentage ?>%</div>
      </div>

      <button id="reviewBtn">Review Answers</button>
    </div>

    <div class="chart-container">
      <canvas id="resultChart"></canvas>
    </div>
  </div>

  <script>
    // Animate Progress Bar
    document.getElementById('progressBar').style.width = '<?= $percentage ?>%';

    // Chart.js Doughnut
    const ctx = document.getElementById('resultChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Correct', 'Incorrect'],
        datasets: [{
          label: 'Performance',
          data: [<?= $correctAnswers ?>, <?= $totalQuestions - $correctAnswers ?>],
          backgroundColor: ['#00f2fe', '#ff6b6b'],
          borderColor: '#222',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
            labels: { color: '#0ff' }
          }
        }
      }
    });

    // Go to Review Answers page
    document.getElementById('reviewBtn').addEventListener('click', () => {
      window.location.href = 'review_answers.php';
    });
  </script>

</body>
</html>
