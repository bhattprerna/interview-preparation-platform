<?php
require_once __DIR__ . '/vendor/autoload.php';
if (!isset($_FILES['resume_file'])) {
    die("No file uploaded.");
}
$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir);
}
$resumeName = basename($_FILES["resume_file"]["name"]);
$targetPath = $uploadDir . time() . "_" . $resumeName;
$fileTmpPath = $_FILES['resume_file']['tmp_name'];
$fileType = mime_content_type($fileTmpPath);
if (!move_uploaded_file($fileTmpPath, $targetPath)) {
    die("Failed to move uploaded file.");
}
$content = "";
$analysisResult = "";
$score = 0;
$feedback = [];
if ($fileType === 'application/pdf') {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($targetPath);
    $content = $pdf->getText();
    if (strpos($content, "@") !== false) {
        $analysisResult .= "✅ Email found. ";
        $score += 20;
    } else {
        $analysisResult .= "❌ Email missing. ";
        $feedback[] = "Include a professional email address.";
    }
    if (preg_match("/\\d{10}/", $content)) {
        $analysisResult .= "✅ Phone number found. ";
        $score += 20;
    } else {
        $analysisResult .= "❌ Phone number missing. ";
        $feedback[] = "Add a valid phone number.";
    }
    if (stripos($content, "Education") !== false) {
        $score += 20;
    } else {
        $feedback[] = "Add an Education section.";
    }
    if (stripos($content, "Experience") !== false) {
        $score += 20;
    } else {
        $feedback[] = "Mention your Experience clearly.";
    }
    if (stripos($content, "Skills") !== false) {
        $score += 20;
    } else {
        $feedback[] = "List your technical or soft skills.";
    }
} elseif ($fileType === 'text/plain') {
    $content = file_get_contents($targetPath);
    $analysisResult = "(Basic analysis for TXT format.)";
} else {
    $analysisResult = "Unsupported file format. Only PDF and TXT supported.";
}
// Prepare analysis for PDF download
$reportHTML = "<h1>Resume Analysis Report</h1><p><strong>Result:</strong> $analysisResult</p><p><strong>Score:</strong> $score/100</p>";
if (!empty($feedback)) {
    $reportHTML .= "<h2>Suggestions</h2><ul>";
    foreach ($feedback as $tip) {
        $reportHTML .= "<li>$tip</li>";
    }
    $reportHTML .= "</ul>";
}
$reportPDFPath = $uploadDir . "report_" . time() . ".pdf";
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($reportHTML);
$mpdf->Output($reportPDFPath, \Mpdf\Output\Destination::FILE);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resume Analysis</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #1c1c1c;
            color: white;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }
        .left-panel {
            width: 65%;
            height: 100vh;
            overflow-y: scroll;
        }
        .right-panel {
            width: 35%;
            background: #2c2c2c;
            padding: 20px;
            box-shadow: -2px 0 5px rgba(0, 240, 255, 0.2);
            position: sticky;
            right: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .card {
            background: #333;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 0 5px #00f0ff;
        }
        h1, h2 {
            color: #00f0ff;
        }
        ul {
            padding-left: 20px;
        }
        iframe {
            width: 100%;
            height: 100vh;
            border: none;
        }
        .download-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00f0ff;
            color: #1c1c1c;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .download-btn:hover {
            background-color: #00d5e6;
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <?php if (str_ends_with($targetPath, '.pdf')): ?>
            <iframe src="<?= $targetPath ?>"></iframe>
        <?php else: ?>
            <p>File uploaded: <a href="<?= $targetPath ?>" target="_blank">View Resume</a></p>
        <?php endif; ?>
    </div>
    <div class="right-panel">
        <h1>Analysis Report</h1>
        <div class="card">
            <h2>Result</h2>
            <p><?= htmlspecialchars($analysisResult) ?></p>
        </div>
        <div class="card">
            <h2>Scorecard</h2>
            <p><strong>Total Score:</strong> <?= $score ?>/100</p>
        </div>
        <?php if (!empty($feedback)): ?>
        <div class="card">
            <h2>Suggestions</h2>
            <ul>
                <?php foreach ($feedback as $tip): ?>
                    <li><?= htmlspecialchars($tip) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <a class="download-btn" href="<?= $reportPDFPath ?>" target="_blank">Download Report PDF</a>
    </div>
</body>
</html>
