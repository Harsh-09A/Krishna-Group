<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['phone']) || !preg_match("/^[0-9]{10}$/", $_POST['phone'])) {
        echo json_encode([
            "alert" => "alert-danger",
            "message" => "Please enter a valid 10-digit phone number!"
        ]);
        exit;
    }

    $phone = htmlspecialchars(trim($_POST['phone']));

    $receiver_email = 'harsh.autowebbed@gmail.com';
    $receiver_name  = 'Krishna Group';
    $subject        = 'Quick Query - Call Back Request';

    $message = "
    <html>
    <body style='font-family:Arial;background:#f4f6f8;padding:20px'>
        <table width='100%' style='max-width:500px;margin:auto;background:#fff;padding:20px;border-radius:10px'>
            <tr>
                <td style='background:#a98e4e;padding:20px;color:#fff;text-align:center'>
                    <h3>Quick Callback Request</h3>
                </td>
            </tr>
            <tr>
                <td style='padding:20px'>
                    <p><strong>Phone Number:</strong> $phone</p>
                </td>
            </tr>
        </table>
    </body>
    </html>";

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'test@autowebbed.com';
        $mail->Password   = 'Test@09871234'; // Move to config file later
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('test@autowebbed.com', 'Quick Query');
        $mail->addAddress($receiver_email, $receiver_name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        echo json_encode([
            "alert" => "alert-success",
            "message" => "Thank you! We will call you shortly."
        ]);

    } catch (Exception $e) {

        echo json_encode([
            "alert" => "alert-danger",
            "message" => "Something went wrong. Please try again."
        ]);
    }

} else {

    echo json_encode([
        "alert" => "alert-danger",
        "message" => "Invalid request!"
    ]);
}
