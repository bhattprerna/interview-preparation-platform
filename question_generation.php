<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title> Question Generation</title>
  <style>
    * {
      margin: 5px;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      background-color: #0d0d0d;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      height: 100vh;
      overflow-x: hidden;
    }
    .wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 60px);
      padding: 20px;
    }
    .container {
      background: #111;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 0 20px cyan;
      text-align: center;
      width: 100%;
      max-width: 550px;
    }
    .avatar {
      width: 70px;
      margin-bottom: 15px;
      animation: float 2s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }
    h2 {
      margin-bottom: 20px;
    }
    select {
      padding: 10px;
      font-size: 16px;
      border-radius: 8px;
      margin-top: 10px;
      margin-bottom: 15px;
      width: 100%;
    }
    .pills {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    .pill {
      padding: 8px 18px;
      border-radius: 20px;
      border: 2px solid cyan;
      background: transparent;
      color: cyan;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s ease;
    }
    .pill.active, .pill:hover {
      background: cyan;
      color: #000;
    }
    button.generate {
      padding: 12px 25px;
      font-size: 16px;
      background: cyan;
      border: none;
      color: #000;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 10px;
      margin-bottom: 20px;
    }
    .loader {
      margin: 10px 0;
      font-style: italic;
      color: #ccc;
    }
    .question-card {
      background: #222;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 15px;
      box-shadow: 0 0 10px cyan;
      text-align: left;
    }
    .question-card .q-type {
      font-size: 14px;
      color: cyan;
    }
    .question-card pre {
      background: #0f0f0f;
      padding: 10px;
      border-radius: 8px;
      color: #00fff2;
      overflow-x: auto;
    }
    .actions {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
    }
    .actions button {
      padding: 8px 15px;
      background: cyan;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 6px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <img src="https://cdn-icons-png.flaticon.com/512/4712/4712104.png" class="avatar" alt="AI Bot" />
      <h2>Hi! I'm your Interview Buddy 🤖</h2>
      <label for="jobRole">Select or Type Job Role:</label><br>
      <input list="jobRoles" id="jobRole" name="jobRole" placeholder="Select or type a role..." style="padding: 10px; font-size: 16px; border-radius: 8px; margin-top: 10px; margin-bottom: 15px; width: 100%;" />
      <datalist id="jobRoles">
      <option value="Data Analyst">
      <option value="Web Developer">
      <option value="AI Engineer">
      </datalist>
      <div style="margin-top: 20px;">Difficulty:</div>
      <div class="pills">
        <div class="pill active" onclick="setDifficulty(this)">Easy</div>
        <div class="pill" onclick="setDifficulty(this)">Medium</div>
        <div class="pill" onclick="setDifficulty(this)">Hard</div>
      </div>
      <button class="generate" onclick="generateQuestions()">🎯 Generate Questions</button>
      <div class="loader" id="loader" style="display: none;">⚙️ Generating questions...</div>
      <div id="questionList"></div>
    </div>
  </div>
  <script>
    let selectedDifficulty = "Easy";
    function setDifficulty(elem) {
      document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
      elem.classList.add('active');
      selectedDifficulty = elem.innerText;
    }
    function generateQuestions() {
      const role = document.getElementById('jobRole').value;
      if (!role) return alert("⚠️ Please select a job role!");
      if (!selectedDifficulty) return alert("⚠️ Please select a difficulty level!");
      window.location.href = `display_questions.php?role=${encodeURIComponent(role)}&difficulty=${encodeURIComponent(selectedDifficulty)}`;
    }
      setTimeout(() => {
        loader.style.display = "none";
        questionBank[role].filter(q => q.difficulty === selectedDifficulty).forEach((q, i) => {
          const div = document.createElement('div');
          div.className = 'question-card';
          let inner = `<div class="q-type"><strong>Q${i+1}:</strong> ${q.question}</div>`;
          if (q.type === "mcq") {
            inner += '<ul>' + q.options.map(opt => `<li><label><input type="radio" name="q${i}"> ${opt}</label></li>`).join('') + '</ul>';
          } else if (q.type === "truefalse") {
            inner += '<label><input type="radio" name="q'+i+'"> True</label> <label><input type="radio" name="q'+i+'"> False</label>';
          } else if (q.type === "code") {
            inner += `<pre>// Write your solution here...</pre>`;
          }
          div.innerHTML = inner;
          list.appendChild(div);
        });
      }, 1000);
  </script>
</body>
</html>