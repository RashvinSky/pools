<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = htmlspecialchars($_POST['name']);
    $email    = htmlspecialchars($_POST['email']);
    $phone    = htmlspecialchars($_POST['phone']);
    $website  = htmlspecialchars($_POST['website']);
    $company  = htmlspecialchars($_POST['company']);
    $message  = htmlspecialchars($_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($website) || empty($company) || empty($message)) {
        die("All fields are required.");
    }

    $to = "your-email@example.com"; // Change this to your actual email
    $subject = "New Quote Request - Website Contact Form";

    $htmlContent = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px; }
            .email-wrapper { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
            .header { text-align: center; margin-bottom: 20px; }
            .header img { max-height: 50px; }
            .field-label { font-weight: bold; color: #333; }
            .field-value { margin: 5px 0 15px; color: #555; }
            .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
        </style>
    </head>
    <body>
        <div class='email-wrapper'>
            <div class='header'>
                <img src='cid:form_logo' alt='Logo' />
                <h2>New Quote Request</h2>
            </div>
            <p><span class='field-label'>Name:</span><br/><span class='field-value'>$name</span></p>
            <p><span class='field-label'>Email:</span><br/><span class='field-value'>$email</span></p>
            <p><span class='field-label'>Phone:</span><br/><span class='field-value'>$phone</span></p>
            <p><span class='field-label'>Website:</span><br/><span class='field-value'>$website</span></p>
            <p><span class='field-label'>Company:</span><br/><span class='field-value'>$company</span></p>
            <p><span class='field-label'>Message:</span><br/><span class='field-value'>$message</span></p>
            <div class='footer'>
                Submitted on " . date("d M Y h:i A") . "
            </div>
        </div>
    </body>
    </html>
    ";

    // Email headers
    $boundary = md5(time());
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/related; boundary=\"$boundary\"\r\n";
    $headers .= "From: Contact Form <no-reply@example.com>\r\n";

    // Message Body
    $messageBody  = "--$boundary\r\n";
    $messageBody .= "Content-Type: text/html; charset=UTF-8\r\n";
    $messageBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $messageBody .= $htmlContent . "\r\n";

    // Embed image (logo)
    $logoPath = "f60a6cac-a24d-42ee-8f93-e397ba74bb28.png"; // Should be in the same directory
    if (file_exists($logoPath)) {
        $fileContent = chunk_split(base64_encode(file_get_contents($logoPath)));
        $messageBody .= "--$boundary\r\n";
        $messageBody .= "Content-Type: image/png; name=\"logo.png\"\r\n";
        $messageBody .= "Content-Transfer-Encoding: base64\r\n";
        $messageBody .= "Content-ID: <form_logo>\r\n";
        $messageBody .= "Content-Disposition: inline; filename=\"logo.png\"\r\n\r\n";
        $messageBody .= $fileContent . "\r\n";
    }

    $messageBody .= "--$boundary--";

    // Send the email
    if (mail($to, $subject, $messageBody, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
?>
