<?php
include"navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Build Resume</title>
    <style>
        body {
            background: #0a0a0a;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            padding: 30px;
        }
        .form-container {
            background: #111;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 15px #00f7ff;
            max-width: 600px;
            margin: 0 auto;
        }
        input, textarea, select {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }
        button {
            background: #00f7ff;
            color: #0a0a0a;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Create Your Resume</h1>
    <div class="form-container">
        <form action="generate_resume.php" method="POST">
            <input type="text" name="fullName" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="education" placeholder="Education" rows="4" required></textarea>
            <textarea name="experience" placeholder="Experience" rows="4" required></textarea>
            <textarea name="skills" placeholder="Skills" rows="4" required></textarea>
            <textarea name="projects" placeholder="Projects" rows="4" required></textarea>
            <textarea name="certifications" placeholder="Certifications" rows="4"></textarea>

            <label for="template">Choose Template:</label>
            <select name="template" id="template" required>
                <option value="classic">Classic</option>
                <option value="modern">Modern</option>
            </select>
            <button type="submit">Generate Resume</button>
        </form>
    </div>
</body>
</html>
