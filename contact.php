<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validate fields
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo "Please complete the form and provide a valid email address.";
        exit;
    }

    // Set recipient email
    $to = "johnnyonion@me.com";

    // Set email subject
    $subject = "New message from johnnyonion.com";

    // Build the email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers
    $headers = "From: $name <$email>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Honeypot spam check
    if (!empty($_POST["website"])) {
        // If the hidden field is filled, likely a bot.
        echo "Spam detected. Submission rejected.";
        exit;
    }

    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validate fields
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
        echo "Please complete the form and provide a valid email address.";
        exit;
    }}

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