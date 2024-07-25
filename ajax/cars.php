<?php

require("../admin/includes/db_config.php");
require("../admin/includes/essentials.php");
date_default_timezone_set('Asia/Kolkata');

session_start();

if (isset($_GET['fetch_cars'])) {

    $chk_avail = json_decode($_GET['chk_avail'], true);

    if ($chk_avail['pickup_date'] != '' && $chk_avail['drop_date'] != '') {

        $today_date = new DateTime(date('Y-m-d'));
        $booking_date  = new DateTime($chk_avail['pickup_date']);
        $drop_date  = new DateTime($chk_avail['drop_date']);

        if ($booking_date == $drop_date) {
            echo "<h3 class='text-center text-danger'>Invalid Date!</h3>";
            exit;
        } else if ($drop_date < $booking_date) {
            echo "<h3 class='text-center text-danger'>Invalid Date!</h3>";
            exit;
        } else if ($booking_date < $today_date) {
            echo "<h3 class='text-center text-danger'>Invalid Date!</h3>";
            exit;
        }
    }


    $fuel_type_list =json_decode($_GET['fuel_type_list'],true);

    $fuel_count = 0;
    $fuel_q = mysqli_query($con, "SELECT `id`,`fuel_type` FROM `car`");
    $fuel_data = '';
    while($fac_row = mysqli_fetch_assoc($fuel_q)){
        if(in_array(($fac_row['id']), $fuel_type_list['fuel_type'])){
            $fuel_count++;
        }
        $fuel_data .="
            <span class='me-1' style='font-weight: 500;'>$fac_row[fuel_type]</span>
        ";
    }
    if(count($fuel_type_list['fuel_type']) != $fuel_count){
        exit();
    }


    $count_cars = 0;
    $output = "";

    $setting_q = "SELECT * FROM `settings` WHERE `sr_no` = 1";
    $setting_r = mysqli_fetch_assoc(mysqli_query($con, $setting_q));


    $car_res = select("SELECT * FROM `car` WHERE `status` = ? AND `removed` = ? ORDER BY `id` DESC", [1, 0], 'ii');

    while ($car_data = mysqli_fetch_assoc($car_res)) {

        if ($chk_avail['pickup_date'] != '' && $chk_avail['drop_date'] != '') {

            $tb_query = "SELECT * FROM `booking_order` WHERE `booking_status`=? AND `car_id`=? AND `dropout_date` > ? AND `booking_date` < ?";

            $values = ['booked', $car_data['id'], $chk_avail['pickup_date'], $chk_avail['drop_date']];
            $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

            // if (($car_data['na'] == 0)) {
            //     continue;
            // }
        }
        $rea_q = mysqli_query($con, "SELECT f.name FROM `features` f INNER JOIN `car_features` rfea ON f.id = rfea.features_id WHERE rfea.car_id = '$car_data[id]' LIMIT 4");

        $features_data = ""; // Initialize the variable outside the loop

        while ($fea_row = mysqli_fetch_assoc($rea_q)) {
            // Concatenate feature data
            $features_data .= "
            <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                $fea_row[name]
            </span>
            ";
        }

        // Thumbnail image

        $car_thumb = CAR_IMG_PATH . "Thumbnail.png";
        $thumb_q = mysqli_query($con, "SELECT * FROM `car_image` WHERE `car_id` = '$car_data[id]' AND `thumb` = 1");
        if (mysqli_num_rows($thumb_q) > 0) {
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $car_thumb =  CAR_IMG_PATH . $thumb_res['image'];
        }

        $book_btn = "";
        if (!$setting_r['shutdown']) {
            $login = 0;
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                $login = 1;
            }
            $book_btn = " <button onclick='checkLoginToBook($login,$car_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>BOOK NOW</button> ";
        }

        $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM 
        `rating_review` WHERE `car_id` = '$car_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

        $rating_res = mysqli_query($con, $rating_q);
        $rating_fetch = mysqli_fetch_assoc($rating_res);

        $rating_data = "";

        if ($rating_fetch['avg_rating'] != NULL) {
            $rating_data = "<td>Rating</td>
            <td>

              ";
            for ($i = 0; $i <= $rating_fetch['avg_rating']; $i++) {
                $rating_data . " <i class='bi bi-star-fill text-warning'></i>";
            }

            $rating_data .= "</td>";
        }

        $output .= "
            <div class='card mb-4 border-0 shadow'>
            <div class='row g-0 p-3 align-items-center'>
                <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                    <img src='$car_thumb' class='img-fluid rounded w-100'>
                </div>
                <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                    <h5 class='mb-1'>$car_data[name]</h5>
                    <div class='card-body'>

                        <h6>$car_data[company]</h6>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Attribute</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sit</td>
                                    <td>$car_data[sit]</td>
                                </tr>
                                <tr>
                                    <td>Fuel Type</td>
                                    <td>$car_data[fuel_type]</td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td>$car_data[model]</td>
                                </tr>
                                <tr>
                                    <td>Features</td>
                                    <td>$features_data</td>
                                </tr>
                                <tr>
                                    $rating_data
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='col-md-2 text-center'>
                    <h6>₹ $car_data[price_day] Per Day</h6>
                    <h6>₹ $car_data[price_hour] Per Hour</h6>
                    $book_btn
                    <a href='car_details.php?id=$car_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none'>MORE DETAILS</a>
                </div>
            </div>
        </div>
     ";
        $count_cars++;
    }

    if ($count_cars > 0) {
        echo $output;
    } else {
        echo "<h3 class='text-center text-danger'>No Cars Found</h3>";
    }
}
