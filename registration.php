<?php
require_once './includes/db_connect.php';
require_once './includes/mail_config.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(32));

    // Save user (not verified)
    $stmt = $conn->prepare("INSERT INTO users (name,email,password,verify_token) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$name,$email,$password,$token);
    $stmt->execute();

    // Build verification link dynamically based on current host/path
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $link = $scheme . '://' . $host . $basePath . '/verify.php?token=' . urlencode($token);

    // Send email
    $mail = new PHPMailer(true);
    try{
        $mail->isSMTP();
        $mail->Host = $SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = $SMTP_USER;
        $mail->Password = $SMTP_PASS; // read from env/config
        $mail->SMTPSecure = 'tls';
        $mail->Port = $SMTP_PORT;

        $fromEmail = $SMTP_FROM ?: $SMTP_USER;
        $fromName = $SMTP_FROM_NAME ?: 'My Website';
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($email,$name);

        $mail->isHTML(true);
        $mail->Subject = 'Verify your account';
        $safeLink = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        $mail->Body = "
            <h2>Email Verification</h2>
            <p>Click the link to verify your account:</p>
            <a href='{$safeLink}'>Verify Account</a>
        ";

        $mail->send();
        echo "Verification email sent! Check your Gmail.";
    }catch(Exception $e){
        echo "Mail error: ".$mail->ErrorInfo;
    }
}
