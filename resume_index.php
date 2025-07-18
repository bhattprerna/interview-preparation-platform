<?php
include "navbar.php";
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume Platform</title>
    <style>
        body {
            background: #0a0a0a;
            font-family: 'Poppins', sans-serif;
            color: #00f7ff;
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            background: #00f7ff;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            margin: 40px;
            border-radius: 10px;
            color: #0a0a0a;
            cursor: pointer;
            box-shadow: 0 0 10px #00f7ff, 0 0 20px #00f7ff;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #000; /* or whatever background color you want */
            z-index: 1000; /* makes sure it stays on top of other elements */
            padding: 10px 0; /* adjust padding if needed */
            }
        .btn:hover {
            background: #00d5ff;}
        input[type="file"] {
            font-size: 18px;   /* Make text bigger */
            padding: 10px;     /* Add padding around */
            background-color: #000; /* Optional: match black background */
            color: cyan;       /* Text color */
            border: 2px solid cyan; /* Glowing border */
            border-radius: 10px;
            box-shadow: 0 0 10px cyan;
            margin:30px;
            /* margin: 40px 0;
            color: #ffffff; */
        }
        .or-text {
            margin: 20px 0;
            font-size: 24px;
            color: cyan;
            font-weight: bold;}
    </style>
</head>
<body>
    <h1>Welcome to Resume Prep!</h1>
    <form action="analyze_resume.php" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
        <input type="file" name="resume_file" required><br>
        <button class="btn" type="submit">Upload Resume for Analysis</button>
    </form>
    <div class="or-text">or</div>
    <form action="build_resume.php" method="GET">
        <button class="btn" type="submit">Build Resume</button>
    </form>
</body>
</html>
