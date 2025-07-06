<?php

$outputDir = __DIR__ . '/static/audio/';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

$audioUrl = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim($_POST['texts'] ?? '');
    $language = $_POST['language'] ?? 'en';

    if (empty($text)) {
        $error = "null";
    } else {
        $filename = uniqid('audio_') . '.mp3';
        $filepath = $outputDir . $filename;

        if ($language === 'ar') {
            $pythonScript = __DIR__ . '/ar.py';
        } else {
            $pythonScript = __DIR__ . '/en.py';
        }

        $escapedText = escapeshellarg($text);

        $command = "python " . escapeshellarg($pythonScript) . " $escapedText " . escapeshellarg($filepath);
        exec($command, $output, $return_var);

        if ($return_var !== 0 || !file_exists($filepath)) {
            $error = "null";
        } else {
            $audioUrl = 'static/audio/' . $filename;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>TextToAudio</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
  <form action="" method="POST">
    <select name="language">
      <option value="en" <?= (isset($language) && $language=='en')?'selected':''; ?>>English</option>
      <option value="ar" <?= (isset($language) && $language=='ar')?'selected':''; ?>>Arabic</option>
    </select>

    <textarea
      class="text"
      placeholder="Enter any text to get audio"
      name="texts"
      required
    ><?= htmlspecialchars($_POST['texts'] ?? '') ?></textarea>

    <button class="get" type="submit">Get</button>
  </form>

  <?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($audioUrl): ?>
    <div style="margin-top:30px; text-align:center;">
      <audio controls autoplay>
        <source src="<?= htmlspecialchars($audioUrl) ?>" type="audio/mpeg" />
        Your browser does not support the audio element.
      </audio>
      <br /><br />
      <a href="<?= htmlspecialchars($audioUrl) ?>" download="speech.mp3" class="get" style="text-decoration:none;">Download Audio</a>
    </div>
  <?php endif; ?>

  <footer class="copyright">
    &copy; 2025 Ammar Nitro. All rights reserved.
  </footer>
</body>
</html>

