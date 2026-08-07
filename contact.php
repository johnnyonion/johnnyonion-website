<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Honeypot spam check
    if (!empty($_POST["website"])) {
        // If the hidden field is filled, likely a bot.
        echo "Spam detected. Submission rejected.";
        exit;
    }

    // Strip CR/LF to prevent email header injection
    $name = str_replace(["\r", "\n"], "", strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    $show = str_replace(["\r", "\n"], "", strip_tags(trim($_POST["show"] ?? "")));
    if (empty($show)) {
        $show = "General";
    }

    // Validate fields
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo "Please complete the form and provide a valid email address.";
        exit;
    }

    // API key lives outside git; see config.example.php for setup
    $configFile = __DIR__ . "/config.php";
    if (!file_exists($configFile)) {
        http_response_code(500);
        echo "Server misconfiguration. Please email johnnyonion@me.com directly.";
        exit;
    }
    require $configFile;

    // Set recipient email
    $to = "johnnyonion@me.com";

    // Set email subject
    $subject = "New message from johnnyonion.com - $show";

    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Show: $show\n\n";
    $email_content .= "Message:\n$message\n";

    // Send via Resend's HTTP API (PHP mail() proved unreliable on this host)
    $payload = json_encode([
        "from" => "johnnyonion.com <noreply@johnnyonion.com>",
        "to" => [$to],
        "reply_to" => "$name <$email>",
        "subject" => $subject,
        "text" => $email_content,
    ]);

    $ch = curl_init("https://api.resend.com/emails");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . RESEND_API_KEY,
        "Content-Type: application/json",
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Send the email
    if ($status >= 200 && $status < 300) {
        header("Location: thank-you.html"); // ✅ Create this page for user confirmation
        exit;
    } else {
        error_log("Resend API error ($status): $response");
        echo "Oops! Something went wrong, and we couldn't send your message.";
    }
} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
