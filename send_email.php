<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendPaymentEmail($to, $month, $year, $kwh, $cost, $method) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = '4gchill@gmail.com';           // SMTP username
        $mail->Password = 'htdisjlcpkvilmuj';              // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('4gchill@gmail.com', 'Electricity Billing System');
        $mail->addAddress($to);                               // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Payment Confirmation';
        $mail->Body    = "Dear customer,<br><br>Your payment of <b>\$$cost</b> for <b>$kwh kWh</b> used in <b>$month $year</b> via <b>$method</b> has been successfully processed.<br><br>Thank you!";
        $mail->AltBody = "Dear customer,\n\nYour payment of \$$cost for $kwh kWh used in $month $year via $method has been successfully processed.\n\nThank you!";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
