<?php
session_start();
include 'navbar.php';
include "db.php";
// ✅ Add this block to handle saving when POST request comes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transcript'])) {
    $transcript = $_POST['transcript'];
    $wpm = $_POST['wpm'];
    $filler_words = $_POST['filler_words'];
    $timestamp = date('Y-m-d H:i:s');
    $sql = "INSERT INTO sessions (transcript, wpm, filler_words, timestamp) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siis", $transcript, $wpm, $filler_words, $timestamp);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit; // ✅ Stop further HTML from loading
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Speech Recognition Practice</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #0d0d0d;
      color: #ffffff;
      margin: 20px;
    }
    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      padding: 40px 20px;
    }
    h1 {
      font-size: 32px;
      color: #00f0ff;
      text-shadow: 0 0 10px #00f0ff;
      margin-bottom: 20px;
    }
    h2 {
      font-size: 24px;
      margin-bottom: 15px;
    }
    p {
      font-size: 16px;
      color: #cccccc;
      margin-bottom: 30px;
    }
    button {
      background: #00f0ff;
      border: none;
      color: #000;
      padding: 12px 24px;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.3s ease;
      margin: 10px;
    }
    button:hover {
      background: #00d9e8;
      transform: scale(1.05);
    }
    .speech-output {
      margin-top: 40px;
      padding: 20px;
      background: #111111;
      border-radius: 15px;
      box-shadow: 0 0 10px #00f0ff;
      min-height: 80px;
      font-size: 18px;
    }
    input, select {
      padding: 8px 12px;
      border-radius: 8px;
      border: none;
      outline: none;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Speech Recognition Practice</h1>
  <div style="margin-bottom: 20px;">
    <label for="jobRoleSelect" style="margin-right: 10px; font-weight: bold;">Select Job Role:</label>
    <input list="jobRoles" id="jobRoleSelect" name="jobRoleSelect" />
    <datalist id="jobRoles">
      <option value="Data Analyst">
      <option value="Web Developer">
      <option value="AI Engineer">
      <option value="DevOps Engineer">
      <option value="Machine Learning Engineer">
    </datalist>
  </div>
  <h2>Mock Interview Question</h2>
  <p id="mockQuestion">Select a role and click "Generate Question".</p>
  <button onclick="nextQuestion()">Generate Question</button>
  <h2>Start Your Speech Practice</h2>
  <button id="practiceButton">Start Practice</button>
  <div class="speech-output" id="speechOutput">
    Your speech will appear here...
  </div>
</div>
<script>
// ✅ Generate mock interview question
function nextQuestion() {
  const jobRole = document.getElementById('jobRoleSelect').value;
  if (!jobRole) {
    document.getElementById('mockQuestion').innerText = 'Please select a job role first.';
    return;
  }
  fetch(`get_mock_question.php?job_role=${encodeURIComponent(jobRole)}`)
    .then(response => response.json())
    .then(data => {
      const questionElement = document.getElementById('mockQuestion');
      if (data.question) {
        questionElement.innerText = data.question;
      } else {
        questionElement.innerText = 'No question found for this role.';
      }
    })
    .catch(error => {
      console.error('Error fetching question:', error);
      document.getElementById('mockQuestion').innerText = 'Failed to load question.';
    });
}
// ✅ Speech recognition logic (IMPROVED VERSION)
const practiceButton = document.getElementById('practiceButton');
const speechOutput = document.getElementById('speechOutput');
let isListening = false;
let recognition; // Declare globally
let startTime = null;
let endTime = null;
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
if (SpeechRecognition) {
  recognition = new SpeechRecognition();
  recognition.lang = 'en-US';
  recognition.interimResults = false;
  recognition.maxAlternatives = 1;
  recognition.onresult = (event) => {
    const speechResult = event.results[0][0].transcript;
    speechOutput.innerText = `Processing your speech...`;
    endTime = new Date(); // Capture end time when user finishes speaking

    const spokenText = speechResult.trim();
    const durationInSeconds = (endTime - startTime) / 1000;
    const durationInMinutes = durationInSeconds / 60;

    const words = spokenText.split(/\s+/).filter(word => word.length > 0);
    const numWords = words.length;
    const wpm = Math.round(numWords / durationInMinutes);

    const fillerWordList = ['um', 'uh', 'like', 'you know', 'so'];
    let fillerCount = 0;
    words.forEach(word => {
      if (fillerWordList.includes(word.toLowerCase())) {
        fillerCount++;
      }
    });
    // Show FULL result
    speechOutput.innerHTML = `
      <h3>📝 Your Practice Result:</h3>
      <p><strong>Transcript:</strong> ${spokenText}</p>
      <p><strong>Words Per Minute (WPM):</strong> ${wpm}</p>
      <p><strong>Filler Words Used:</strong> ${fillerCount}</p>
      <p><strong>Duration:</strong> ${durationInSeconds.toFixed(2)} seconds</p>
    `;
    // ✅ OPTIONAL: Save to server
    fetch('', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `transcript=${encodeURIComponent(spokenText)}&wpm=${wpm}&filler_words=${fillerCount}`
    })
    .then(response => response.json())
    .then(data => {
      console.log('Session saved successfully!');
    })
    .catch(error => {
      console.error('Error saving session:', error);
    });
  };
  recognition.onerror = (event) => {
    speechOutput.innerText = `Error occurred: ${event.error}`;
    stopListening();
  };
  recognition.onend = () => {
    if (isListening) {
      // Don't auto-restart because we want to capture full speech
      isListening = false;
      practiceButton.textContent = 'Start Practice';
    }
  };
} else {
  speechOutput.innerText = "Speech Recognition not supported in your browser.";
}
practiceButton.addEventListener('click', () => {
  if (!SpeechRecognition) {
    return;
  }
  if (!isListening) {
    startListening();
  } else {
    stopListening();
  }
});
function startListening() {
  recognition.start();
  isListening = true;
  practiceButton.textContent = 'Stop Practice';
  speechOutput.innerText = "Listening... Speak now!";
  startTime = new Date(); // Start time captured
}
function stopListening() {
  recognition.stop();
  isListening = false;
  practiceButton.textContent = 'Start Practice';
}
</script>
</body>
</html>
