<?php
ob_start(); // Start output buffering to handle redirects

// Get val_id from POST
$val_id = urlencode($_POST['val_id'] ?? '');
if (empty($val_id)) {
    error_log("Missing val_id");
    die("Invalid or missing val_id.");
}

// SSLCommerz configuration with your actual sandbox credentials
$store_id = urlencode("your_store_id"); // Replace with your actual store ID
$store_passwd = urlencode("your_store_password"); // Replace with your actual store password
$requested_url = "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=" . $val_id . "&store_id=" . $store_id . "&store_passwd=" . $store_passwd . "&v=1&format=json";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $requested_url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2); // Enable hostname verification
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // Disable for sandbox; enable with certificate for production
curl_setopt($handle, CURLOPT_TIMEOUT, 30);

$result = curl_exec($handle);

if ($result === false) {
    $error = curl_error($handle);
    error_log("cURL Error: " . $error);
    echo "Failed to connect with SSLCOMMERZ. Error: " . htmlspecialchars($error);
    curl_close($handle);
    exit;
}

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
curl_close($handle);

if ($code == 200) {
    $result = json_decode($result);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Decode Error: " . json_last_error_msg());
        echo "Invalid response from SSLCOMMERZ.";
        exit;
    }

    // TRANSACTION INFO
    $status = $result->status ?? 'INVALID';
    $tran_date = $result->tran_date ?? '';
    $tran_id = $result->tran_id ?? '';
    $val_id = $result->val_id ?? '';
    $amount = $result->amount ?? 0;
    $store_amount = $result->store_amount ?? 0;
    $bank_tran_id = $result->bank_tran_id ?? '';
    $card_type = $result->card_type ?? '';

    // EMI INFO
    $emi_instalment = $result->emi_instalment ?? '';
    $emi_amount = $result->emi_amount ?? '';
    $emi_description = $result->emi_description ?? '';
    $emi_issuer = $result->emi_issuer ?? '';

    // ISSUER INFO
    $card_no = $result->card_no ?? '';
    $card_issuer = $result->card_issuer ?? '';
    $card_brand = $result->card_brand ?? '';
    $card_issuer_country = $result->card_issuer_country ?? '';
    $card_issuer_country_code = $result->card_issuer_country_code ?? '';

    // API AUTHENTICATION
    $APIConnect = $result->APIConnect ?? '';
    $validated_on = $result->validated_on ?? date('Y-m-d H:i:s');
    $gw_version = $result->gw_version ?? '';

    // Database update based on status
    require_once __DIR__ . '/../includes/db_connect.php';
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        echo "Database error. Please contact support.";
        exit;
    }

    if ($status === 'VALID') {
        $stmt = $conn->prepare("UPDATE donations SET payment_status = 'Completed', validated_on = ?, bank_tran_id = ?, card_type = ? WHERE tran_id = ? AND payment_status = 'Pending'");
        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            echo "Database prepare error: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param("ssss", $validated_on, $bank_tran_id, $card_type, $tran_id);
            if ($stmt->execute()) {
                echo "<div style='text-align: center; padding: 20px; font-family: Arial, sans-serif;'>";
                echo "<h2>Payment Successful!</h2>";
                echo "Amount: " . htmlspecialchars($amount) . " BDT<br>";
                echo "Transaction ID: " . htmlspecialchars($tran_id) . "<br><br>";
                echo "<a href='https://clonesunnah.rf.gd/index.php' style='display: inline-block; padding: 10px 20px; background-color: #008e48; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>Go to Home Page</a>";
                echo "</div>";
            } else {
                echo "Error updating payment status: " . htmlspecialchars($conn->error);
            }
            $stmt->close();
        }
    } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
        $stmt = $conn->prepare("UPDATE donations SET payment_status = 'Canceled' WHERE tran_id = ? AND payment_status = 'Pending'");
        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            echo "Database prepare error: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param("s", $tran_id);
            if ($stmt->execute()) {
                echo "<div style='text-align: center; padding: 20px; font-family: Arial, sans-serif;'>";
                echo "<h2>Payment Cancelled!</h2>";
                echo "Transaction ID: " . htmlspecialchars($tran_id) . "<br><br>";
                echo "<a href='https://clonesunnah.rf.gd/index.php' style='display: inline-block; padding: 10px 20px; background-color: #ff4444; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>Return to Home Page</a>";
                echo "</div>";
            } else {
                echo "Error updating cancellation status: " . htmlspecialchars($conn->error);
            }
            $stmt->close();
        }
    } else {
        echo "Payment validation status unknown: " . htmlspecialchars($status);
    }

    $conn->close();
} else {
    error_log("HTTP Error $code: " . ($result ?: 'No response'));
    echo "Failed to connect with SSLCOMMERZ. HTTP Error: $code";
}

ob_end_flush();
?>