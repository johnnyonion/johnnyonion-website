<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Honeypot spam check
    if (!empty($_POST["website"])) {
        // If the hidden field is filled, likely a bot.
        echo "Spam detected. Submission rejected.";
        exit;
    }

    // Strip CR/LF to prevent email header injection via the From header below
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

    // Set recipient email
    $to = "johnnyonion@gmail.com";

    // Set email subject
    $subject = "New message from johnnyonion.com - $show";

    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Show: $show\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers
    // From must be domain-matching for SPF/DKIM alignment, or strict
    // receivers (e.g. iCloud) silently drop the message. Visitor's real
    // address goes in Reply-To so replies still reach them.
    $headers = "From: johnnyonion.com <noreply@johnnyonion.com>\r\n";
    $headers .= "Reply-To: $name <$email>";

    // Send the email
    if (mail($to, $subject, $email_content, $headers)) {
        header("Location: thank-you.html"); // ✅ Create this page for user confirmation
        exit;
    } else {
        echo "Oops! Something went wrong, and we couldn't send your message.";
    }
} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
