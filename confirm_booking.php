    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <?php
        require("includes/links.php");
        ?>
        <title><?php echo $setting_r['site_title']; ?> - CONFIRM BOOKING</title>
        <style>

        </style>
    </head>

    <body class="bg-light">
        <?php
        require("includes/header.php");
        ?>

        <?php

        /* 
            Check car id from url is present or not
            Shutdown mode is active or not
            User is logged in or not

        */

        if (!isset($_GET['id']) || $setting_r['shutdown'] == true) {
            redirect("cars.php");
        } else if (!(isset($_SESSION['login']) && $_SESSION['login']) == true) {
            redirect("cars.php");
        }

        // filteration and get user data

        $data = filteration($_GET);

        $car_res = select("SELECT * FROM `car` WHERE `id` = ? AND `status` = ? AND `removed` = ?", [$data['id'], 1, 0], 'iii');

        if (mysqli_num_rows($car_res) == 0) {
            redirect("cars.php");
        }

        $car_data = mysqli_fetch_assoc($car_res);

        $_SESSION['car'] = [
            'id' => $car_data['id'],
            'name' => $car_data['name'],
            'company' => $car_data['company'],
            'model' => $car_data['model'],
            'price_day' => $car_data['price_day'],
            'price_hour' => $car_data['price_hour'],
            'payment' => null,
            'available' => false,
        ];



        $user_res = select("SELECT * FROM `user_cred` WHERE `id` = ? LIMIT 1", [$_SESSION['uId']], 'i');
        $user_data = mysqli_fetch_assoc($user_res);

        ?>



        <div class="container">
            <div class="row">
                <div class="col-12 my-5 mb-4 px-4">
                    <h2 class="fw-bold h-font ">
                        CONFIRM BOOKING
                    </h2>
                    <div style="font-size: 14px;">
                        <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                        <span class="text-secondary"> > </span>
                        <a href="cars.php" class="text-secondary text-decoration-none">CARS</a>
                        <span class="text-secondary"> > </span>
                        <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
                    </div>

                </div>

                <div class="col-lg-7 col-md-12 px-4">

                    <?php

                    $car_thumb = CAR_IMG_PATH . "Thumbnail.png";
                    $thumb_q = mysqli_query($con, "SELECT * FROM `car_image` WHERE `car_id` = '$car_data[id]' AND `thumb` = 1");
                    if (mysqli_num_rows($thumb_q) > 0) {
                        $thumb_res = mysqli_fetch_assoc($thumb_q);
                        $car_thumb =  CAR_IMG_PATH . $thumb_res['image'];
                    }

                    echo <<<data
                        <div class="card p-3 border-0 shadow-sm rounded-3">
                            <img src="$car_thumb" class="img-fluid rounded mb-3">
                            <h5>$car_data[name] - $car_data[company] </h5>
                            <h6>₹ $car_data[price_day] Per Day</h6>
                            <h6>₹ $car_data[price_hour] Per Hour</h6>
                        </div>
                    data;
                    ?>

                </div>

                <div class="col-lg-5 col-md-12 px-4">
                    <div class="card mb-4 border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <form action="pay_now.php" method="POST" id="booking_form">
                                <h6 class="mb-3 h-font fw-bold">BOOKING DETAILS</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label mb-1">Name</label>
                                        <input name="name" type="text" value="<?php echo $user_data['name']; ?>" class="form-control shadow-none" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label mb-1">Phone No</label>
                                        <input name="phone_no" type="number" value="<?php echo $user_data['phone_no']; ?>" class="form-control shadow-none" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address'], " , ", $user_data['pincode']; ?></textarea>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label mb-1">Booking - in</label>
                                        <input name="bookingin" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label mb-1">Drop - out</label>
                                        <input name="dropout" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="spinner-border text-info mb-3  d-none" id="info_loader">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <h6 class="mb-3 text-danger" id="pay_info">Select Pick up and Drop out Date and time</h6>
                                        <button type="submit" name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>

                                    </div>
                                </div>

                                </from>
                        </div>
                    </div>

                </div>
            </div>
        </div>
         

        <?php
        require("includes/footer.php");
        ?>

        <script>
            let booking_form = document.getElementById("booking_form");
            let info_loader = document.getElementById("info_loader");
            let pay_info = document.getElementById("pay_info");

            function check_availability() {

                let bookingin_val = booking_form.elements["bookingin"].value;
                let dropout_val = booking_form.elements["dropout"].value;
                booking_form.elements["pay_now"].setAttribute("disabled", true);


                if (bookingin_val != "" && dropout_val != "") {

                    pay_info.classList.add("d-none");
                    pay_info.classList.replace("text-dark", "text-danger");
                    info_loader.classList.remove("d-none");

                    let data = new FormData();

                    data.append("check_availability", '');
                    data.append("bookingin", bookingin_val);
                    data.append("dropout", dropout_val);

                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'ajax/confirm_booking.php', true);

                    xhr.onload = function() {
                        let data = JSON.parse(this.responseText);
                        if (data.status == "booking_in_drop_out_same") {
                            pay_info.innerText = "You Can Not Drop Out Same Day!";
                        } else if (data.status == "booking_date_past") {
                            pay_info.innerText = "Booking Date Can Not Be Past Date!";
                        } else if (data.status == "dropout_date_past") {
                            pay_info.innerText = "Drop Out Date Can Not Be Past Date!";
                        } else if (data.status == "unavailable") {
                            pay_info.innerText = "Car Not Available For This Date!";
                        } else {
                            pay_info.innerHTML = "No of Days : " + data.days + "<br>Total Amount To Pay : ₹ " + data.payment;
                            pay_info.classList.replace("text-danger", "text-dark");
                            booking_form.elements["pay_now"].removeAttribute("disabled");
                        }

                        pay_info.classList.remove("d-none");
                        info_loader.classList.add("d-none");

                    }
                    xhr.send(data);


                }
            }


         
        </script>
    </body>

    </html>