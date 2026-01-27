<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, name, email, password, is_verified FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (!$user['is_verified']) {
            $message = '❌ Please verify your email first.';
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: /assunna/pages/verify_success.php');
            exit();
        } else {
            $message = '❌ Wrong password.';
        }
    } else {
        $message = '❌ User not found.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login</title>
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
  <h1>Login</h1>
  <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" name="login">Login</button>
  </form>
  <div class="actions">
    <a href="/assunna/pages/signup.php">Create an account</a>
  </div>
</div>
</body>
</html>
