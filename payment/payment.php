<?php
// payment.php
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0.00;

// Validate amount
if ($amount <= 0) {
    die("Invalid or missing amount! Debug: Received " . (isset($_GET['amount']) ? $_GET['amount'] : 'null'));
}

// SSLCommerz configuration
$tran_id = isset($_GET['tran_id']) ? $_GET['tran_id'] : ("SSLCZ_TEST_" . uniqid());
$post_data = array();
$post_data['store_id']     = "nsdte69777c30c199d";
$post_data['store_passwd'] = "nsdte69777c30c199d@ssl";
$post_data['total_amount'] = $amount;
$post_data['currency']     = "BDT";
$post_data['tran_id']      = $tran_id;
$post_data['success_url']  = "http://localhost/AsSunna/payment/success.php";
$post_data['fail_url']     = "http://localhost/AsSunna/payment/fail.php";
$post_data['cancel_url']   = "http://localhost/AsSunna/payment/cancel.php";
// v4 required product fields
$post_data['product_name']     = 'Donation';
$post_data['product_category'] = 'general';
$post_data['product_profile']  = 'general';
$post_data['shipping_method']  = 'NO';

// CUSTOMER INFORMATION (minimal set)
$post_data['cus_name']     = "Test Customer";
$post_data['cus_email']    = "test@test.com";
$post_data['cus_add1']     = "Dhaka";
$post_data['cus_city']     = "Dhaka";
$post_data['cus_postcode'] = "1000";
$post_data['cus_country']  = "Bangladesh";
$post_data['cus_phone']    = "01711111111";

$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
// Send as application/x-www-form-urlencoded
curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
// Avoid 100-Continue issues and set content type
curl_setopt($handle, CURLOPT_HTTPHEADER, ['Expect:', 'Content-Type: application/x-www-form-urlencoded']);
// For production set to true and ensure proper CA root is available
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

$content = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code == 200 && !(curl_errno($handle))) {
    curl_close($handle);
    $sslcommerzResponse = $content;
} else {
    $err = curl_error($handle);
    curl_close($handle);
    echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
    if ($err) {
        echo ' - ' . htmlspecialchars($err);
    }
    exit;
}

# PARSE THE JSON RESPONSE
$sslcz = json_decode($sslcommerzResponse, true);

if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
    echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
    exit;
} else {
    echo "JSON Data parsing error!<br />";
    echo '<pre>' . htmlspecialchars($sslcommerzResponse) . '</pre>';
}
?>