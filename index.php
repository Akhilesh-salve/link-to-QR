<?php
// Check if the form is submitted and generate the QR code
if (isset($_POST['url']) && !empty($_POST['url'])) {
    require_once('phpqrcode/qrlib.php');
    
    $url = trim($_POST['url']);
    
    // Validate URL format
    if (!filter_var($url, FILTER_VALIDATE_URL) && !preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $url)) {
        // If it doesn't look like a URL, prepend http://
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'http://' . $url;
        }
    }
    
    // Create a temporary directory if it doesn't exist
    $tempDir = 'temp/';
    if (!is_dir($tempDir)) {
        if (!mkdir($tempDir, 0755, true)) {
            $error = "Failed to create temp directory";
        }
    }
    
    if (!isset($error)) {
        // Generate unique filename to avoid conflicts
        $fileName = 'qrcode_' . uniqid() . '.png';
        $filePath = $tempDir . $fileName;
        
        try {
            // Generate the QR code with high error correction, scale 10, margin 2
            QRcode::png($url, $filePath, QR_ECLEVEL_H, 10, 2);
            $qrGenerated = true;
        } catch (Exception $e) {
            $error = "Failed to generate QR code: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superman QR Code Generator</title>
    <style>
        /* Basic reset and centering */
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f7f7f7, #e3e3e3);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            background-color: #ffffff;
            border: 3px solid #0000FF; /* Blue border */
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }
        
        h1 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #FF0000; /* Red header */
            font-size: 2.2em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #FF0000; /* Red border for input */
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #0000FF;
            box-shadow: 0 0 5px rgba(0,0,255,0.3);
        }
        
        input[type="submit"] {
            background-color: #0000FF; /* Blue button */
            color: #FFFF00; /* Yellow text */
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        input[type="submit"]:hover {
            background-color: #0000CC;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .qr-code {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .qr-code h2 {
            color: #0000FF;
            margin-bottom: 15px;
        }
        
        .qr-code img {
            max-width: 100%;
            height: auto;
            border: 2px solid #0000FF;
            border-radius: 8px;
            padding: 10px;
            background-color: white;
        }
        
        .download-btn {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #ffffff;
            background-color: #FF0000; /* Red download button */
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .download-btn:hover {
            background-color: #CC0000;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .error {
            color: #FF0000;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solid #ffcccc;
        }
        
        .url-display {
            margin: 10px 0;
            padding: 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
            font-family: monospace;
            word-break: break-all;
            color: #333;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🦸‍♂️ Superman QR</h1>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" name="url" placeholder="Enter URL (e.g., google.com or https://example.com)" 
                       value="<?php echo isset($_POST['url']) ? htmlspecialchars($_POST['url']) : ''; ?>" required>
            </div>
            <input type="submit" value="Generate QR Code">
        </form>
        
        <?php
        if (isset($error)) {
            echo "<div class='error'>Error: " . htmlspecialchars($error) . "</div>";
        }
        
        if (isset($qrGenerated) && $qrGenerated) {
            echo "<div class='qr-code'>";
            echo "<h2>Your QR Code:</h2>";
            echo "<div class='url-display'>URL: " . htmlspecialchars($url) . "</div>";
            echo "<img src='" . htmlspecialchars($filePath) . "' alt='QR Code for " . htmlspecialchars($url) . "'>";
            echo "<br><a class='download-btn' href='" . htmlspecialchars($filePath) . "' download='qrcode.png'>📥 Download QR Code</a>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
