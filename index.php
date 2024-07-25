<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <?php
    require("includes/links.php")
    ?>
    <title><?php echo $setting_r['site_title']; ?>- HOME</title>

    <style>
        .swiper-slide img {
            height: 500px;
        }

        .availability-form {
            margin-top: -70px;
            z-index: 2;
            position: relative;
        }

        @media screen and (max-width: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }


        .swiper1 {
            width: 100%;
            height: 50%;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            background-color: #a0d8e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body class="bg-light">


    <?php
    require("includes/header.php");

    ?>


    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('carousel');
                while ($row = mysqli_fetch_assoc($res)) {
                    $path = CAROUSEL_IMG_PATH;
                    echo <<<data
                            
                            <div class="swiper-slide">
                                <img src="$path$row[image]" class="w-100 d-block" />
                            </div>
                        data;
                }

                ?>
            </div>

        </div>
    </div>

    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5>Check Availability</h5>
                <form action="cars.php">
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Pick Up Date</label>
                            <input type="date" class="form-control shadow-none" name="pick_date" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Drop Date</label>
                            <input type="date" class="form-control shadow-none" name="drop_date" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Select Fuel Type</label>
                            <select class="form-select shadow-none" name="fuel_type">
                                <?php
                                $fuel_type_q = mysqli_query($con, "SELECT DISTINCT `fuel_type` FROM `car`");

                                while ($fuel_type_res = mysqli_fetch_assoc($fuel_type_q)) {
                                    echo "<option value='" . $fuel_type_res['fuel_type'] . "'>" . $fuel_type_res['fuel_type'] . "</option>";
                                }
                                ?>

                                <option selected>Select Fuel Type</option>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Select Car Type</label>
                            <select class="form-select shadow-none" name="car_type">
                                <?php
                                $fuel_type_q = mysqli_query($con, "SELECT DISTINCT `car_type` FROM `car`");

                                while ($fuel_type_res = mysqli_fetch_assoc($fuel_type_q)) {
                                    echo "<option value='" . $fuel_type_res['car_type'] . "'>" . $fuel_type_res['car_type'] . "</option>";
                                }
                                ?>
                                <option selected>Select Car Type</option>

                            </select>

                        </div>
                        <input type="hidden" name="check_availability">
                        <div class="col-lg-3 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-white shadow-none float-right custom-bg p-3 mt-3">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR CARS</h2>
    <div class="container">
        <div class="row">

            <?php
            $car_res = select("SELECT * FROM `car` WHERE `status` = ? AND `removed` = ? ORDER BY `id` DESC LIMIT 3", [1, 0], 'ii');
            while ($car_data = mysqli_fetch_assoc($car_res)) {
                $rea_q = mysqli_query($con, "SELECT f.name FROM `features` f INNER JOIN `car_features` rfea ON f.id = rfea.features_id WHERE rfea.car_id = '$car_data[id]'");

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
                    $book_btn = " <button onclick='checkLoginToBook($login,$car_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>BOOK NOW</button> ";
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
                    for ($i = 1; $i < $rating_fetch['avg_rating']; $i++) {
                        $rating_data . " <i class='bi bi-star-fill text-warning'></i>";
                    }

                    $rating_data .= "</td>";
                }


                echo <<<data
                    <div class="col-lg-4 col-md-6 my-3">
                        <div class="card border-0 shadow" style="max-width: 350px; margin:auto; ">
                            <img class="card-img-top" src="$car_thumb" class="card-img-top">
                            <div class="card-body">
                                <h5>$car_data[name] - $car_data[company]</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Attribute</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Pricing:</td>
                                            <td>
                                            ₹ $car_data[price_day] Per Day <br>
                                            ₹ $car_data[price_hour] Per Hour
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fuel</td>
                                            <td>$car_data[fuel_type]</td>
                                        </tr>
                                        <tr>
                                            <td>Car type</td>
                                            <td>$car_data[car_type]</td>
                                        </tr>
                                        <tr>
                                           $rating_data
                                        </tr>

                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-evenly mb-2 ">
                                    $book_btn
                                    <a href="car_details.php?id=$car_data[id]" class="btn btn-sm btn-outline-dark shadow-none">MORE DETAILS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                 data;
            }
            ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="cars.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Cars >>></a>
            </div>
        </div>
    </div>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR FACILITIES</h2>
    <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
        <?php
        $queryResult = mysqli_query($con, "SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 3");
        $path = FACILITIES_IMG_PATH;

        while ($row = mysqli_fetch_assoc($queryResult)) {
            echo <<<data
                <div class="col-lg-3 col-md-3 mb-5 px-4 bg-white text-center rounded shadow py-4 my-2 justify-content-evenly">
                    <img src="$path$row[icon]" width="40px">
                    <h5 class="m-0 ms-3">$row[name]</h5>
                </div>
            data;
        }

        ?>
        <div class="col-lg-12 text-center mt-5">
            <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities >>></a>
        </div>
    </div>
</div>



    <!-- <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR OFFERS</h2> -->

    <!-- 
    <div class="container mt-5">
        <div #swiperRef="" class="swiper1 swiper-offer ">
            <div class="swiper-wrapper mb-5  ">
                <div class="swiper-slide bg-white p-4 rounded shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon-circle">
                                <i class="bi bi-percent"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h1 class="h5">Flat 20% off</h1>
                            <p>Flat 20% off</p>
                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div> -->




    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>
    <div class="container mt-5">
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5">

                <?php
                $review_q = "SELECT rr.*,uc.name AS uname, c.name AS cname, uc.profile FROM `rating_review`rr
                INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                INNER JOIN `car` c  ON rr.car_id = c.id
                ORDER BY `sr_no` DESC LIMIT 6";

                $review_res = mysqli_query($con, $review_q);
                $img_path = USERS_IMG_PATH;

                if (mysqli_num_rows($review_res) == 0) {
                    echo "No reviews Yet!";
                } else {
                    while ($row = mysqli_fetch_assoc($review_res)) {
                        $stars = "<i class='bi bi-star-fill text-warning'></i> ";
                        for ($i = 0; $i <= $row['rating']; $i++) {
                            $stars .= " <i class='bi bi-star-fill text-warning'></i>";
                        }
                        echo <<<slides
                        <div class="swiper-slide bg-white p-4">
                            <div class="profile d-flex align-items-center mb-3"> 
                            <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                                <h6 class="m-0 ms-2">$row[uname]</h6>
                            </div>
                            <p>
                                $row[review]
                            </p>
                            <div class="rating">
                                $stars 
                            </div>
                        </div>
                   slides;
                    }
                }
                ?>
                <div class="swiper-pagination"></div>
            </div>
            <div class="col-lg-12 text-center mt-5">
                <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More></a>
            </div>
        </div>
    </div>


    <?php
    $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no` = ? ";
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q, $values, "i"));

    ?>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>

    <div class="container">
        <div class="row">
            <div class="col lg-8 col-md-7 p-4 mb-lg-0 mb-3 bg-white rounded shadow">
                <iframe class="w-100 rounded" src="<?php echo $contact_r['iframe'] ?>" height="400" loading="lazy"></iframe>
            </div>
            <div class="col lg-4 col-md-4">
                <div class="bg-white p-4 ms-4 rounded mb-4 shadow">
                    <h5>Call Us</h5>
                    <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-telephone-outbound-fill"></i> +
                        <?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php
                    if ($contact_r['pn2'] != "") {
                        echo <<<data
                                <a href="tel: +{$contact_r['pn2']}" class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-telephone-outbound-fill"></i> + {$contact_r['pn2']}</a>
                            data;
                    }
                    ?>
                    <br><br>
                    <h5>Mail</h5>
                    <a href="mailto:<?php echo $contact_r['email'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-envelope-fill"></i>
                        <?php echo $contact_r['email'] ?>
                    </a>

                </div>

                <div class="bg-white p-4 ms-4 rounded mb-4 shadow">
                    <h5>Follow Us</h5>
                    <?php
                    if ($contact_r['twitter'] != "") {
                        echo <<<data
                            <a href="$contact_r[twitter]" class="d-inline-block mb-3" target="_blank">
                                <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-twitter me-1"></i> Twitter</span>
                            </a>
                            <br>
                            data;
                    }
                    ?>
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-instagram me-1"></i>
                            Instagram</span>
                    </a>
                    <br>
                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-facebook me-1"></i>
                            Facebook</span>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="recoveryModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="recovery-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center fw-bold h-font"><i class="bi bi-shield-lock fs-3 me-2"></i> Create A New Password</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">New Password </label>
                            <input type="password" name="password" class="form-control shadow-none" required>
                            <input type="hidden" name="email">
                            <input type="hidden" name="token">
                        </div>

                        <div class="mb-2 text-end">
                            <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn btn-dark shadow-none">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    require("includes/footer.php")
    ?>

    <?php
    if (isset($_GET['account_recovery'])) {
        $data = filteration($_GET);
        $t_date = date("Y-m-d");
        $query = select("SELECT * FROM `user_cred` WHERE `email` = ? AND `token` = ? AND `t_expire` = ? LIMIT 1", [$data['email'], $data['token'], $t_date], 'sss');
        if (mysqli_num_rows($query) == 1) {
            echo <<<showModal
                    <script>
                        var myModal = document.getElementById('recoveryModel');
                        myModal.querySelector("input[name='email']").value = '$data[email]';
                        myModal.querySelector("input[name='token']").value = '$data[token]';
                        var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                        modal.show();
                    </script>
                showModal;
        } else {
            alert("error", "Invalid OR Expired Link!");
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js "></script>
    <script src="assets/js/main.js "></script>
    <script>
        var swiper = new Swiper(".swiper-container ", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
        });

        var swiper = new Swiper('#swiper-container', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            slidesPerView: 3, // Fixed number of displayed cards
            spaceBetween: 20, // Space between each card
        });

        var swiper = new Swiper(".swiper-offer", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });

        var swiper = new Swiper(".swiper-testimonials", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
            },
        });

        let recovery_form = document.getElementById('recovery-form');

        recovery_form.addEventListener('submit', (e) => {
            e.preventDefault();
            let data = new FormData();

            data.append('email', recovery_form.elements['email'].value);
            data.append('token', recovery_form.elements['token'].value);
            data.append('password', recovery_form.elements['password'].value);
            data.append('recover_password', '');

            var myModal = new bootstrap.Modal(document.getElementById('recoveryModel'));
            myModal.hide();

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/login_register.php', true);
            xhr.onload = function() {
                if (this.responseText == 'failed') {
                    alert('error', 'Account Reset Failed!');
                } else {
                    alert('success', 'Account Reset Successful!');
                    recovery_form.reset();

                }
            }
            xhr.send(data);
        });
    </script>
</body>

</html>