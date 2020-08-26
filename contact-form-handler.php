<?php
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'admin@whatip.xyz';
$mail->Password = 'Support!123456';
$mail->setFrom('admin@whatip.xyz', 'Tomas');
$mail->addAddress('tandzelis@gmail.com', 'Tomas');
if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
    $mail->Subject = 'PHPMailer contact form';
    $mail->isHTML(false);
    $mail->Body = <<<EOT
Email: {$_POST['email']}
Name: {$_POST['name']}
Message: {$_POST['message']}
EOT;
    if (!$mail->send()) {
        $msg = 'Sorry, something went wrong. Please try again later.';
    } else {
        header('Location: http://www.tomas.lt:8008/contact.php?msg=Thank%20you%20for%20your%20message');
    }
} else {
    $msg = 'Share it with us!';
}
?>


}