<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <?php
    require("includes/links.php");
    ?>
    <title><?php echo $setting_r['site_title']; ?> - CONFIRM STATUS</title>
    <style>

    </style>
</head>

<body class="bg-light">
    <?php
    require("includes/header.php");
    ?>



    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold h-font ">
                    PAYMENT STATUS
                </h2>

                <?php

                $frm_data = filteration($_GET);
                if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {
                    redirect("index.php");
                }

                $booking_q = "SELECT bo.*,bd.*  FROM `booking_order` bo INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id WHERE bo.order_id = ? AND bo.user_id = ? AND bo.status != ?";

                $booking_res = select($booking_q, [$frm_data['order'], $_SESSION['uId'], 'pending'], 'sis');

                if (mysqli_num_rows($booking_res) == 0) {
                    redirect("index.php");
                }

                $booking_fetch = mysqli_fetch_assoc($booking_res);

                if ($booking_fetch['trans_status'] == 'TXN_SUCCESS') {
                    echo <<<date
                                <div class = "col-12  px-4">
                                    <p class = "h-font fw-bold alert alert-success">
                                        <i class = "bi bi-check-circle-fill"></i>
                                        Payment Done! Booking Confirmed
                                        <br><br>
                                        <a href = "bookings.php">Go to Booking</a>
                                        
                                    </p>
                                </div>
                           date;
                } else {
                    echo <<<date
                            <div class = "col-12  px-4">
                                <p class = "h-font fw-bold alert alert-danger">
                                    <i class = "bi bi-exclamation-triangle-fill"></i>
                                    Payment Failed! $booking_fetch[trans_resp_msg]
                                    <br><br>
                                    <a href = "bookings.php">Go to Booking</a>
                                    
                                </p>
                            </div>
                           date;
                }

                ?>

            </div>
        </div>
    </div>

    <?php
    require("includes/footer.php");
    ?>

    
</body>

</html>