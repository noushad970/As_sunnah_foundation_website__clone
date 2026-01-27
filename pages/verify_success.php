<?php
session_start();
$name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Verification Success</title>
<style>
body { font-family: Poppins, Arial, sans-serif; background:#f5f7fb; margin:0; }
.container { max-width: 580px; margin: 80px auto; background:#fff; padding:28px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); text-align:center; }
h1 { margin-bottom: 12px; }
p { color:#333; }
a.button { display:inline-block; margin-top:18px; background:#6b46c1; color:#fff; padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:600; }
a.button:hover { background:#553c9a; }
</style>
</head>
<body>
<div class="container">
  <h1>Welcome<?php echo $name ? ', ' . htmlspecialchars($name) : ''; ?>!</h1>
  <p>Your email is verified and you are logged in.</p>
  <a class="button" href="/assunna/pages/index.php">Go to Home</a>
</div>
</body>
</html>
