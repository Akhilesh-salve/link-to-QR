<?php
// Check if the form is submitted and generate the QR code
if (isset($_POST['url'])) {
    require_once('phpqrcode/qrlib.php');

    $url = trim($_POST['url']);
    // Create a temporary directory if it doesn't exist
    $tempDir = 'temp/';
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
    }
    $fileName = 'qrcode.png';
    $filePath = $tempDir . $fileName;

    // Generate the QR code with high error correction, scale 10, margin 2
    QRcode::png($url, $filePath, QR_ECLEVEL_H, 10, 2);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Superman QR Code Generator</title>
    <style>
        /* Basic reset and centering */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border: 3px solid #0000FF; /* Blue border */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        h1 {
            margin-top: 0;
            color: #FF0000; /* Red header */
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            border: 2px solid #FF0000; /* Red border for input */
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #0000FF; /* Blue button */
            color: #FFFF00; /* Yellow text */
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            opacity: 0.9;
        }
        .qr-code {
            margin-top: 20px;
        }
        a.download-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #ffffff;
            background-color: #FF0000; /* Red download button */
            padding: 8px 16px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Superman QR</h1>
        <form method="post" action="">
            <input type="text" name="url" placeholder="Enter URL" required>
            <br>
            <input type="submit" value="Generate QR Code">
        </form>
        <?php
        if (isset($_POST['url'])) {
            echo "<div class='qr-code'>";
            echo "<h2>Your QR Code:</h2>";
            echo "<img src='$filePath' alt='QR Code'>";
            echo "<br><a class='download-btn' href='$filePath' download='qrcode.png'>Download QR Code</a>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
