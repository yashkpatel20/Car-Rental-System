<?php

require('admin/includes/db_config.php');
require('admin/includes/essentials.php');

require('includes/paytm/config_paytm.php');
require('includes/paytm/encdec_paytm.php');

date_default_timezone_set('Asia/Kolkata');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login']) == true) {
    redirect("index.php");
}

if (isset($_POST['pay_now'])) {
    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

    $checkSum = "";
    $paramList = array();

    $ORDER_ID = 'ORD' . $_SESSION['uId'] . random_int(11111, 99999);
    $CUST_ID = $_SESSION['uId'];
    $INDUSTRY_TYPE_ID = INDUSTRY_TYPE_ID;
    $CHANNEL_ID = CHANNEL_ID;
    $TXN_AMOUNT = $_SESSION['car']['price_day'];


    // Create an array having all required parameters for creating checksum.
    $paramList["MID"] = PAYTM_MERCHANT_MID;
    $paramList["ORDER_ID"] = $ORDER_ID;
    $paramList["CUST_ID"] = $CUST_ID;
    $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
    $paramList["CHANNEL_ID"] = $CHANNEL_ID;
    $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
    $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

    $paramList["CALLBACK_URL"] = CALLBACK_URL;

    //Here checksum string will return by getChecksumFromArray() function.
    $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

    $frm_data = filteration($_POST);


    $query1 = "INSERT INTO `booking_order`(`user_id`, `car_id`, `booking_date`, `dropout_date`, `order_id`) VALUES (?, ?, ?, ?, ?)";

    insert($query1, [$CUST_ID, $_SESSION['car']['id'], $frm_data['bookingin'], $frm_data['dropout'], $ORDER_ID], "issss");

    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `car_name`, `car_company`, `price`, `total_pay`,  `user_name`, `phone_no`, `address`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

    insert($query2, [$booking_id, $_SESSION['car']['name'], $_SESSION['car']['company'], $_SESSION['car']['price_day'], $TXN_AMOUNT, $frm_data['name'], $frm_data['phone_no'], $frm_data['address']], "isssssss");
}

?>

<html>

<head>
    <title>Processing</title>
</head>

<body>

    <h1>Please do not refresh this page...</h1>
    <form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">

        <?php
        foreach ($paramList as $name => $value) {
            echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
        }
        ?>
        <input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">


    </form>
    <script type="text/javascript">
        document.f1.submit();
    </script>
   <?php

require('admin/includes/db_config.php');
require('admin/includes/essentials.php');

require('includes/paytm/config_paytm.php');
require('includes/paytm/encdec_paytm.php');

date_default_timezone_set('Asia/Kolkata');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login']) == true) {
    redirect("index.php");
}

if (isset($_POST['pay_now'])) {
    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");

    $checkSum = "";
    $paramList = array();

    $ORDER_ID = 'ORD' . $_SESSION['uId'] . random_int(11111, 99999);
    $CUST_ID = $_SESSION['uId'];
    $INDUSTRY_TYPE_ID = INDUSTRY_TYPE_ID;
    $CHANNEL_ID = CHANNEL_ID;
    $TXN_AMOUNT = $_SESSION['car']['price_day'];


    // Create an array having all required parameters for creating checksum.
    $paramList["MID"] = PAYTM_MERCHANT_MID;
    $paramList["ORDER_ID"] = $ORDER_ID;
    $paramList["CUST_ID"] = $CUST_ID;
    $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
    $paramList["CHANNEL_ID"] = $CHANNEL_ID;
    $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
    $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

    $paramList["CALLBACK_URL"] = CALLBACK_URL;

    //Here checksum string will return by getChecksumFromArray() function.
    $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

    $frm_data = filteration($_POST);


    $query1 = "INSERT INTO `booking_order`(`user_id`, `car_id`, `booking_date`, `dropout_date`, `order_id`) VALUES (?, ?, ?, ?, ?)";

    insert($query1, [$CUST_ID, $_SESSION['car']['id'], $frm_data['bookingin'], $frm_data['dropout'], $ORDER_ID], "issss");

    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `booking_details`(`booking_id`, `car_name`, `car_company`, `price`, `total_pay`,  `user_name`, `phone_no`, `address`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

    insert($query2, [$booking_id, $_SESSION['car']['name'], $_SESSION['car']['company'], $_SESSION['car']['price_day'], $TXN_AMOUNT, $frm_data['name'], $frm_data['phone_no'], $frm_data['address']], "isssssss");
}

?>

<html>

<head>
    <title>Processing</title>
</head>

<body>

    <h1>Please do not refresh this page...</h1>
    <form method="post" action="<?php echo PAYTM_TXN_URL ?>" name="f1">

        <?php
        foreach ($paramList as $name => $value) {
            echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
        }
        ?>
        <input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">


    </form>
    <script type="text/javascript">
        document.f1.submit();
    </script>

     <!-- Replace the "test" client-id value with your client-id -->
     <script src="https://www.paypal.com/sdk/js?client-id=ARfUujlywRb0FiD7SBFf1ME1L5BaxrSF1pnGlMxIqXT4Ew2gIeaMN3svKnRqMRqEBnVcPQ_c6M1xq52C&currency=USD"></script>
</body>

</html>
</body>

</html>