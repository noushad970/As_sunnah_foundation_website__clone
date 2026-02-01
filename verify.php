<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/includes/db_connect.php';

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
if ($token === '') {
    echo '❌ Missing verification token.';
    exit;
}

$stmt = $conn->prepare('SELECT id FROM users WHERE verify_token = ?');
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $upd = $conn->prepare('UPDATE users SET is_verified = 1, verify_token = NULL WHERE verify_token = ?');
    $upd->bind_param('s', $token);
    if ($upd->execute()) {
        $_SESSION['flash'] = 'Email verified successfully! You can now login.';
        header('Location: pages/login.php');
        $upd->close();
        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo '❌ Failed to update verification status.';
    }
    $upd->close();
} else {
    echo '❌ Invalid or expired token.';
}

$stmt->close();
$conn->close();
?>
