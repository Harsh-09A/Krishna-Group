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

    $receiver_email = 'info@thekrishnagroup.co';
    $receiver_name  = 'Krishna Group';
    $subject        = 'Website Contact Form Filled';

    /* ---------------------------
       Sanitize & Format Fields
    ----------------------------*/
    $fields = [];

    foreach ($_POST as $name => $value) {

        if (empty($value)) continue;

        $name = ucwords(str_replace('_', ' ', $name));

        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        $fields[$name] = htmlspecialchars(trim($value));
    }

    $rows = '';

    foreach ($fields as $key => $val) {
        $rows .= "
        <tr>
            <td style='padding:8px 0;font-weight:600'>$key:</td>
            <td style='padding:8px 0'>$val</td>
        </tr>";
    }

    /* ---------------------------
       Email Template
    ----------------------------*/
    $message = "
    <!DOCTYPE html>
    <html>
    <body style='background:#f4f6f8;padding:20px;font-family:Arial'>
        <table width='100%' style='max-width:600px;margin:auto;background:#fff;border-radius:10px'>
            <tr>
                <td style='background:#a98e4e;padding:30px;color:#fff;text-align:center'>
                    <h2 style='margin:0'>Contact Form Filled</h2>
                </td>
            </tr>
            <tr>
                <td style='padding:25px'>
                    <p>Hello <strong>$receiver_name</strong>,</p>
                    <p>You have received a new enquiry:</p>
                    <table width='100%'>$rows</table>
                    <p style='margin-top:25px'>
                        Best wishes,<br>
                        <strong>DigIN Team</strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td style='background:#f1f1f1;text-align:center;padding:12px;font-size:12px;color:#888'>
                    This is an automated message. Please do not reply.
                </td>
            </tr>
        </table>
    </body>
    </html>";

    /* ---------------------------
       PHPMailer Setup
    ----------------------------*/
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'test@autowebbed.com';
        $mail->Password   = 'Test@09871234'; // ⚠ move to config file in production
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // 7.x recommended
        $mail->Port       = 465;

        // Optional debugging
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->setFrom('test@autowebbed.com', 'Website Enquiry');
        // $mail->addReplyTo($_POST['email'], $_POST['name'] ?? '');
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addCC('diginmediaprivatelimited@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        echo json_encode([
            "alert" => "alert-success",
            "message" => "Message sent successfully!"
        ]);

    } catch (Exception $e) {

        echo json_encode([
            "alert" => "alert-danger",
            "message" => "Mailer Error: " . $mail->ErrorInfo
        ]);
    }

} else {

    echo json_encode([
        "alert" => "alert-danger",
        "message" => "Invalid request method!"
    ]);
}
