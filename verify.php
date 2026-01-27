<?php
 n   require_once __DIR__ . '/includes/db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE verify_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE verify_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        echo "✅ Email verified successfully! You can now login.";
    } else {
        echo "❌ Invalid or expired token.";
    }
} else {
    echo "❌ Missing verification token.";
}
