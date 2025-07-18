<?php
include 'navbar.php';
include 'db.php';
$role = $_GET['role'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';
$questions = [];
if ($role && $difficulty) {
  $stmt = $conn->prepare("SELECT * FROM questions WHERE job_role = ? AND difficulty = ?");
  $stmt->bind_param("ss", $role, $difficulty);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
  }
  $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz Questions</title>
  <style>
    body {
      background-color: #0d0d0d;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .question-card {
      background: #222;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px cyan;
      margin-bottom: 20px;
    }
    .q-type {
      color: cyan;
      margin-bottom: 5px;
    }
    pre {
      background: #111;
      padding: 10px;
      border-radius: 8px;
      overflow-x: auto;
    }
    .actions {
      text-align: center;
      margin-top: 30px;
    }
    .actions button {
      background: cyan;
      color: #000;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
      margin: 0 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h2>Questions for <?= htmlspecialchars($role) ?> - <?= htmlspecialchars($difficulty) ?></h2>
  <?php if (count($questions) > 0): ?>
    <div id="questionList">
      <?php foreach ($questions as $index => $q): ?>
        <div class="question-card">
          <div class="q-type"><strong>Q<?= $index+1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></div>
          <?php if ($q['type'] === 'mcq'): ?>
            <?php
              $opts = explode('|', $q['options']);
              $optLabels = ['A', 'B', 'C', 'D'];
              foreach ($opts as $i => $opt):
                $label = $optLabels[$i];
            ?>
              <div>
                <label>
                  <input type="radio" name="q<?= $index ?>" value="<?= $label ?>"> <?= htmlspecialchars(trim($opt)) ?>
                </label>
              </div>
            <?php endforeach; ?>
          <?php elseif ($q['type'] === 'truefalse'): ?>
            <div>
              <label><input type="radio" name="q<?= $index ?>" value="A"> True</label>
              <label><input type="radio" name="q<?= $index ?>" value="B"> False</label>
            </div>
          <?php elseif ($q['type'] === 'code'): ?>
            <pre>// Write your code here</pre>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="actions">
      <button id="submitBtn">Submit</button>
    </div>
  <?php else: ?>
    <h3 style="text-align:center; color: lightgray;">⚠️ No questions found for the selected role and difficulty.</h3>
  <?php endif; ?>
<script>
document.getElementById('submitBtn').addEventListener('click', function() {
    let correctAnswers = 0;
    const totalQuestions = <?= count($questions) ?>;
    const reviewData = [];
    <?php foreach ($questions as $index => $q): ?>
        <?php if ($q['type'] === 'mcq' || $q['type'] === 'truefalse'): ?>
            const selectedOption<?= $index ?> = document.querySelector('input[name="q<?= $index ?>"]:checked');
            if (selectedOption<?= $index ?>) {
                const userAnswer = selectedOption<?= $index ?>.value.trim();
                const correctAnswer = "<?= substr(trim($q['answer']), 0, 1) ?>"; // Get 'A', 'B', etc.
                const optionText = selectedOption<?= $index ?>.nextSibling.textContent.trim();
                reviewData.push({
                    question: "<?= htmlspecialchars($q['question']) ?>",
                    correctAnswer: "<?= htmlspecialchars(trim($q['answer'])) ?>",
                    userAnswer: optionText
                });
                if (userAnswer === correctAnswer) {
                    correctAnswers++;
                }
            }
        <?php endif; ?>
    <?php endforeach; ?>
    // Save review data
    localStorage.setItem('reviewData', JSON.stringify(reviewData));
    const totalMarks = totalQuestions * 10;
    const obtainedMarks = correctAnswers * 10;
    const username = "test_user"; 
    const percentage = Math.round((correctAnswers / totalQuestions) * 100);
    // Save stats to localStorage
    localStorage.setItem('totalMarks', obtainedMarks);
    localStorage.setItem('correctAnswers', correctAnswers);
    localStorage.setItem('totalQuestions', totalQuestions);
    // Send to backend
    fetch('save_results.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            username: username,
            total_marks: obtainedMarks,
            correct_answers: correctAnswers,
            total_questions: totalQuestions,
            percentage: percentage
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        window.location.href = 'results.php';
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
</body>
</html>
