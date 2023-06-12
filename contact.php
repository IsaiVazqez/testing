<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        ini_set('sendmail_from', $from);
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $sub = strip_tags(trim($_POST["subject"]));
        $phone = strip_tags(trim($_POST["phone"]));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);

        ini_set('sendmail_from', $email);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            exit;
        }

        // Set the recipient email address.
        $recipient = "alex.agc@outlook.com";
        //$recipient = "Info@rosmexico.com";

        // Set the email subject.
        $subject = "Mensaje de contacto: $sub por  $name";

        // Build the email content.
        $email_content = "Nombre: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Tel: $phone\n\n";
        $email_content .= "Mensaje:\n$message\n";

        // Build the email headers.
        $email_headers = "De: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
    }

?>