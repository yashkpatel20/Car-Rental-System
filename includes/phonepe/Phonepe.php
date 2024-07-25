<?php

require('admin/includes/db_config.php');
require('admin/includes/essentials.php');

date_default_timezone_set('Asia/Kolkata');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login']) == true) {
    redirect("index.php");
}

if (isset($_POST['pay_now'])) {
    // Assuming you have a logic to calculate $count_days
    $bookingin = new DateTime($_POST['bookingin']);
    $dropout = new DateTime($_POST['dropout']);
    $count_days = $dropout->diff($bookingin)->days;

    // Generate a random ORDER_ID
    $ORDER_ID = 'ORD' . $_SESSION['uId'] . random_int(11111, 99999);

    // Calculate the transaction amount
    $TXN_AMOUNT = $_SESSION['car']['price_day'] * $count_days;

    // Check if payment was successful
    $trans_status = '';

    if (isset($final['data']['instrumentResponse']['status']) && $final['data']['instrumentResponse']['status'] === 'SUCCESS') {
        // Payment successful
        $trans_status = "PAYMENT_SUCCESS";
        $trans_resp_msg = "Payment successfully completed";
    } else {
        // Payment failed
        $trans_status = "PAYMENT_FAILED";
        $trans_resp_msg = "Payment failed";
    }

    // Insert data into the booking_order table
    $query1 = "INSERT INTO `booking_order`(`user_id`, `car_id`, `booking_date`, `dropout_date`, `order_id`, `trans_amt`, `trans_resp_msg`, `trans_status`, `booking_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    insert($query1, [
        $_SESSION['uId'],
        $_SESSION['car']['id'],
        $bookingin->format('Y-m-d'),
        $dropout->format('Y-m-d'),
        $ORDER_ID,
        $TXN_AMOUNT,
        'Transaction initiated',
        $trans_status,
        'booked' // Set the booking status to "booked"
    ], "isssssdss");

    // Get the last inserted booking ID
    $booking_id = mysqli_insert_id($con);

    // Insert data into the booking_details table
    $query2 = "INSERT INTO `booking_details`(`booking_id`, `car_name`, `car_company`, `price`, `total_pay`,  `user_name`, `phone_no`, `address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    insert($query2, [
        $booking_id,
        $_SESSION['car']['name'],
        $_SESSION['car']['company'],
        $_SESSION['car']['price_day'],
        $TXN_AMOUNT,
        $_POST['name'],
        $_POST['phone_no'],
        $_POST['address']
    ], "isssssss");

    // Prepare data for PhonePe API request
    $data = [
        "merchantId" => "PGTESTPAYUAT",
        "merchantTransactionId" => "MT" . random_int(111111, 999999),
        "merchantUserId" => "MUID123",
        "amount" => $TXN_AMOUNT,
        "redirectUrl" => "http://localhost/crs/includes/phonepe/redirect-url.php",
        "redirectMode" => "POST",
        "callbackUrl" => "http://localhost/crs/includes/phonepe/callback-url.php",
        "mobileNumber" => "9999999999",
        "paymentInstrument" => [
            "type" => "PAY_PAGE"
        ]
    ];

    // Create the X-VERIFY header for API request
    $saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
    $saltIndex = 1;
    $encode = json_encode($data);
    $encoded = base64_encode($encode);
    $string = $encoded . '/pg/v1/pay' . $saltKey;
    $sha256 = hash('sha256', $string);
    $finalXHeader = $sha256 . '###' . $saltIndex;

    // Send request to PhonePe API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'accept: application/json',
        'X-VERIFY: ' . $finalXHeader,
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['request' => $encoded]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $final = json_decode($response, true);

    // Check if the payment URL is available
    if (isset($final['data']['instrumentResponse']['redirectInfo']['url'])) {
        // Redirect the user to the PhonePe payment page
        header('Location: ' . $final['data']['instrumentResponse']['redirectInfo']['url']);
        exit;
    } else {
        // Handle the case where 'url' is not set
    }

    // Update the trans_id and booking_status in the booking_order table
    $trans_id = $final['data']['instrumentResponse']['merchantTransactionId'];
    $query4 = "UPDATE `booking_order` SET `trans_id` = ?, `trans_status` = ?, `trans_resp_msg` = ?, `booking_status` = ? WHERE `order_id` = ?";
    update($query4, [$trans_id, $trans_status, 'Transaction initiated', 'booked', $ORDER_ID], "sssss");

    // Insert the payment details into the database
    $query3 = "INSERT INTO `payment_details`(`booking_id`, `amount`, `trans_status`, `trans_resp_msg`) VALUES (?, ?, ?, ?)";
    insert($query3, [$booking_id, $TXN_AMOUNT, $trans_status, $trans_resp_msg], "idss");

    // Close cURL connection
    curl_close($ch);
}
?>

<html>

<head>
    <title>Processing</title>
</head>

<body>

    <h1>Please do not refresh this page...</h1>
    <!-- Your existing HTML form, uncomment if needed -->

</body>

</html>
