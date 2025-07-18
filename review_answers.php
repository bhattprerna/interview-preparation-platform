<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Review Answers</title>
  <style>
    body {
      background: #111;
      color: #0ff;
      font-family: 'Poppins', sans-serif;
      padding: 30px;
      margin: 0;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .review-card {
      background: #222;
      padding: 20px;
      margin: 15px auto;
      border-radius: 12px;
      width: 80%;
      box-shadow: 0 0 10px cyan;
    }

    .question {
      font-weight: bold;
      margin-bottom: 10px;
    }

    .answer {
      margin: 5px 0;
    }

    .correct {
      color: lime;
    }

    .wrong {
      color: red;
    }

    .back-btn {
      display: block;
      margin: 30px auto;
      padding: 10px 20px;
      background: #00f2fe;
      color: #111;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h1>📝 Review Your Answers</h1>

<div id="reviewList"></div>

<button class="back-btn" onclick="goBack()">🔙 Back to Results</button>

<script>
  const reviewData = JSON.parse(localStorage.getItem('reviewData')) || [];

  const reviewList = document.getElementById('reviewList');

  if (reviewData.length === 0) {
    reviewList.innerHTML = "<h3 style='text-align:center;'>No review data available!</h3>";
  } else {
    reviewData.forEach((item, index) => {
      const card = document.createElement('div');
      card.className = 'review-card';
      card.innerHTML = `
        <div class="question">Q${index + 1}: ${item.question}</div>
        <div class="answer">Your Answer: <span class="${item.userAnswer === item.correctAnswer ? 'correct' : 'wrong'}">${item.userAnswer}</span></div>
        <div class="answer">Correct Answer: <span class="correct">${item.correctAnswer}</span></div>
      `;
      reviewList.appendChild(card);
    });
  }

  function goBack() {
    window.location.href = 'results.php';
  }
</script>

</body>
</html>
