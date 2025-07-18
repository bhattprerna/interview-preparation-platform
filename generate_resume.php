<?php
// Generate the resume as PDF
require_once __DIR__ . '/vendor/autoload.php'; // Make sure you have mpdf installed
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$education = $_POST['education'];
$experience = $_POST['experience'];
$skills = $_POST['skills'];
$projects = $_POST['projects'];
$certifications = $_POST['certifications'];
$template = $_POST['template'];
use Mpdf\Mpdf;
$mpdf = new \Mpdf\Mpdf();
ob_start();
if ($template == "classic") {
    ?>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; }
        h1 { font-size: 18pt; }
        h2 { font-size: 14pt; margin-bottom: 5px; }
        hr { border: 1px solid #000; }
    </style>
    <h1><?= htmlspecialchars($fullName) ?></h1>
    <p>Email: <?= htmlspecialchars($email) ?> | Phone: <?= htmlspecialchars($phone) ?></p>
    <hr>
    <h2>Education</h2>
    <p><?= nl2br(htmlspecialchars($education)) ?></p>
    <h2>Experience</h2>
    <p><?= nl2br(htmlspecialchars($experience)) ?></p>
    <h2>Skills</h2>
    <p><?= nl2br(htmlspecialchars($skills)) ?></p>
    <h2>Projects</h2>
    <p><?= nl2br(htmlspecialchars($projects)) ?></p>
    <h2>Certifications</h2>
    <p><?= nl2br(htmlspecialchars($certifications)) ?></p>
    <?php
} elseif ($template == "modern") {
    ?>
    <style>
        body { font-family: 'Helvetica Neue', sans-serif; font-size: 12pt; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 10px; }
        h1 { color: #4CAF50; font-size: 24pt; }
        h2 { color: #555; font-size: 16pt; margin-top: 20px; }
        p { color: #333; }
        .section { margin-bottom: 15px; }
    </style>
    <div class="container">
        <h1><?= htmlspecialchars($fullName) ?></h1>
        <p>Email: <?= htmlspecialchars($email) ?> | Phone: <?= htmlspecialchars($phone) ?></p>
        <div class="section"><h2>🎓 Education</h2><p><?= nl2br(htmlspecialchars($education)) ?></p></div>
        <div class="section"><h2>💼 Experience</h2><p><?= nl2br(htmlspecialchars($experience)) ?></p></div>
        <div class="section"><h2>🛠 Skills</h2><p><?= nl2br(htmlspecialchars($skills)) ?></p></div>
        <div class="section"><h2>🚀 Projects</h2><p><?= nl2br(htmlspecialchars($projects)) ?></p></div>
        <div class="section"><h2>📜 Certifications</h2><p><?= nl2br(htmlspecialchars($certifications)) ?></p></div>
    </div>
    <?php
}
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->Output('My_Resume.pdf', 'D'); // 'D' = Download
?>
