<?php
// Read val_id from POST or GET safely
$val_id = isset($_POST['val_id']) ? $_POST['val_id'] : ($_GET['val_id'] ?? '');
if ($val_id === '' || $val_id === null) {
    exit('Missing val_id');
}
$val_id = urlencode($val_id);

// Match credentials with payment.php
$store_id = urlencode('nsdte69777c30c199d');
$store_passwd = urlencode('nsdte69777c30c199d@ssl');
$requested_url = 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=' . $val_id . '&store_id=' . $store_id . '&store_passwd=' . $store_passwd . '&v=1&format=json';

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $requested_url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); // local only
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // local only

$result = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code == 200 && !(curl_errno($handle))) {
    $result = json_decode($result);
    if (!$result) {
        curl_close($handle);
        exit('Invalid validator response');
    }

    // TRANSACTION INFO
    $status = $result->status ?? '';
    $tran_date = $result->tran_date ?? '';
    $tran_id = $result->tran_id ?? '';
    $val_id_resp = $result->val_id ?? '';
    $amount = $result->amount ?? '';
    $store_amount = $result->store_amount ?? '';
    $bank_tran_id = $result->bank_tran_id ?? '';
    $card_type = $result->card_type ?? '';

    // Treat both VALID and VALIDATED as success
    if ($status === 'VALID' || $status === 'VALIDATED') {
        require_once __DIR__ . '/../includes/db_connect.php';
        $validated_on = $result->validated_on ?? '';
        $stmt = $conn->prepare("UPDATE donations SET payment_status = 'Completed', validated_on = ?, bank_tran_id = ?, card_type = ? WHERE tran_id = ? AND payment_status = 'Pending'");
        $stmt->bind_param('ssss', $validated_on, $bank_tran_id, $card_type, $tran_id);
        if ($stmt->execute()) {
            echo 'Payment successful! Amount: ' . htmlspecialchars((string)$amount) . ' BDT, Transaction ID: ' . htmlspecialchars((string)$tran_id);
        } else {
            echo 'Error updating payment status: ' . htmlspecialchars($conn->error);
        }
        $stmt->close();
        $conn->close();
    } else {
        // Print raw response for diagnosis
        echo 'Payment validation failed. Status: ' . htmlspecialchars((string)$status) . '<br />';
        echo '<pre>' . htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)) . '</pre>';
    }
} else {
    echo 'Failed to connect with SSLCOMMERZ';
}

curl_close($handle);
?>