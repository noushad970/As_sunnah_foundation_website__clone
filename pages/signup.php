<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/mail_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passwordRaw = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $passwordRaw === '') {
        $message = '❌ All fields are required.';
    } else {
        // Check if email already exists
        $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->bind_param('s', $email);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
        $check->close();
        if ($exists) {
            $message = '❌ Email already registered. Please login or use another email.';
        } else {
            $token = bin2hex(random_bytes(32));
            $password = password_hash($passwordRaw, PASSWORD_BCRYPT);

            $stmt = $conn->prepare('INSERT INTO users (name, email, password, is_verified, verify_token) VALUES (?, ?, ?, 0, ?)');
            $stmt->bind_param('ssss', $name, $email, $password, $token);
            if ($stmt->execute()) {
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                $verifyLink = $scheme . '://' . $host . $basePath . '/../verify.php?token=' . urlencode($token);

                $mail = new PHPMailer(true);
                try {
                    // Debugging and connectivity tweaks
                    $mail->SMTPDebug = 2; // verbose debug
                    $mail->Debugoutput = 'error_log'; // log to PHP error log

                    $mail->isSMTP();
                    // Force IPv4 to avoid localhost IPv6 DNS issues
                    $mail->Host = gethostbyname($SMTP_HOST);
                    $mail->SMTPAuth = true;
                    $mail->Username = $SMTP_USER;
                    $mail->Password = $SMTP_PASS;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = $SMTP_PORT;
                    // Relax SSL checks for local dev (remove in production)
                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true,
                        ],
                    ];

                    // Always use authenticated account as From to satisfy Gmail SMTP
                    $mail->setFrom($SMTP_USER, $SMTP_FROM_NAME ?: 'My Website');
                    $mail->addAddress($email, $name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Verify your account';
                    $safeLink = htmlspecialchars($verifyLink, ENT_QUOTES, 'UTF-8');
                    $mail->Body = "<h2>Email Verification</h2><p>Click the link to verify your account:</p><a href='{$safeLink}'>Verify Account</a>";
                    $mail->send();
                    $message = '✅ Registration successful. Check your email to verify your account.';
                } catch (Exception $e) {
                    $message = '❌ Mail error: ' . $mail->ErrorInfo;
                }
            } else {
                // Graceful fallback for any SQL errors (including duplicate key)
                $message = '❌ Registration failed.';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Sign Up</title>
<style>
body { font-family: Poppins, Arial, sans-serif; background:#f5f7fb; margin:0; }
.container { max-width: 420px; margin: 60px auto; background:#fff; padding:24px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); }
h1 { text-align:center; }
form { display:flex; flex-direction:column; gap:12px; }
input { padding:10px 12px; border:1px solid #ddd; border-radius:8px; }
button { background:#6b46c1; color:#fff; border:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-weight:600; }
button:hover { background:#553c9a; }
.message { text-align:center; margin-bottom:10px; }
.actions { text-align:center; margin-top:10px; }
.actions a { color:#6b46c1; text-decoration:none; }
</style>
</head>
<body>
<div class="container">
  <h1>Create Account</h1>
  <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
  <form method="POST" action="">
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" name="register">Sign Up</button>
  </form>
  <div class="actions">
    <a href="/assunna/pages/login.php">Already have an account? Login</a>
  </div>
</div>
</body>
</html>
