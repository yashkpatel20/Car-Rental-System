<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Car Rental - CAR DETAILS</title>
    <?php
    require("includes/links.php");
    ?>
    <style>

    </style>
</head>

<body class="bg-light">
    <?php
    require("includes/header.php");
    ?>

    <?php
    if (!isset($_GET['id'])) {
        redirect("cars.php");
    }
    $data = filteration($_GET);

    $car_res = select("SELECT * FROM `car` WHERE `id` = ? AND `status` = ? AND `removed` = ?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($car_res) == 0) {
        redirect("cars.php");
    }

    $car_data = mysqli_fetch_assoc($car_res);

    ?>



    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold h-font ">
                    <?php echo $car_data['name'], " ", $car_data['company'], " - ", $car_data['model'] ?>
                </h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="cars.php" class="text-secondary text-decoration-none">CARS</a>
                </div>

            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <div id="carCarousel" class="carousel slide carousel-fade" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $car_img = CAR_IMG_PATH . "Thumbnail.png";
                        $img_q = mysqli_query($con, "SELECT * FROM `car_image` WHERE `car_id` = '$car_data[id]' ");

                        if (mysqli_num_rows($img_q) > 0) {
                            $active_class = 'active';

                            while ($img_res = mysqli_fetch_assoc($img_q)) {
                                echo "<div class='carousel-item $active_class '>
                            <img src='" . CAR_IMG_PATH . $img_res['image'] . "' class='img-fluid d-block w-100 rounded '>
                          </div>";
                                $active_class = '';
                            }
                        } else {
                            echo "<div class='carousel-item active'>
                            <img src='$car_img' class='d-block w-100'>
                        </div>";
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-target="#carCarousel" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-target="#carCarousel" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php
                        echo <<<price
                                <h4>₹ $car_data[price_day] Per Day</h4>
                                <h4>₹ $car_data[price_hour] Per Hour</h4>
                           price;

                        $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM 
                           `rating_review` WHERE `car_id` = '$car_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

                        $rating_res = mysqli_query($con, $rating_q);
                        $rating_fetch = mysqli_fetch_assoc($rating_res);

                        $rating_data = "";

                        if ($rating_fetch['avg_rating'] != NULL) {
                            for ($i = 0; $i <= $rating_fetch['avg_rating']; $i++) {
                                $rating_data . " <i class='bi bi-star-fill text-warning'></i>";
                            }
                        }


                        echo <<<rating
                            <div class="mb-3">
                                $rating_data
                            </div>
                        rating;

                        $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f INNER JOIN `car_features` rfea ON f.id = rfea.features_id WHERE rfea.car_id = '$car_data[id]'");
                        $features_data = "";
                        while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                            $features_data .= "
                                    <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                        $fea_row[name]
                                    </span>
                                    ";
                        }

                        echo <<<features
                                <div class="mb-3">
                                    <h5>Features</h5>
                                    $features_data
                                </div>
                        features;

                        if (!$setting_r['shutdown']) {
                            $login = 0;
                            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                                $login = 1;
                            }
                            echo <<<book
                                <button onclick='checkLoginToBook($login,$car_data[id])' class="btn w-100 text-white custom-bg shadow-none mb-1">BOOK NOW</button>
                            book;
                        }

                        ?>

                    </div>
                </div>

            </div>

            <div class="col-lg-12 col-md-12 px-4 mt-4">
                <div class="card mb-4 border-0 shadow">
                    <div class="mb-4 px-2 mt-4 ">
                        <h5 class="fw-bold h-font">Description</h5>
                        <p>
                            <?php echo $car_data['description'] ?>
                        </p>
                    </div>
                </div>
                <div>

                </div>
            </div>


            <div class="col-lg-12 mt-4 px-4 ">

                <div class="card mb-4 border-0 shadow">

                    <div class="mb-4 px-2 mt-4 ">
                        <h5 class="fw-bold h-font">Reviews & Ratings</h5>
                        <?php
                        $review_q = "SELECT rr.*,uc.name AS uname, c.name AS cname, uc.profile FROM `rating_review`rr
                        INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                        INNER JOIN `car` c  ON rr.car_id = c.id
                        WHERE rr.car_id = '$car_data[id]'
                        ORDER BY `sr_no` DESC LIMIT 15";

                        $review_res = mysqli_query($con, $review_q);
                        $img_path = USERS_IMG_PATH;

                        if (mysqli_num_rows($review_res) == 0) {
                            echo "No reviews Yet!";
                        } else {
                            while ($row = mysqli_fetch_assoc($review_res)) {
                                $stars = "<i class='bi bi-star-fill text-warning'></i> ";
                                for ($i = 0; $i < $row['rating']; $i++) {
                                    $stars .= " <i class='bi bi-star-fill text-warning'></i>";
                                }
                                echo <<<reviews
                                    <div class="mb-4">
                                        <div class=" d-flex align-items-center mb-2">
                                                <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                                                <h6 class="m-0 ms-2">$row[uname]</h6>
                                                
                                        </div>
                                            <p class="mb-1">
                                                $row[review]
                                            </p>
                                            <div class="rating">
                                                $stars
                                            </div>
                                    </div>
                                reviews;
                            }
                        }
                        ?>
                    </div>

                </div>
            </div>



            <div class="col-lg-12 col-md-12 px-4 mt-4">

            </div>

            <div class="col-lg-12 mt-4 px-4 ">

                <?php

                echo <<<data
                    
                        <div class=" card mb-4 border-0 shadow">
                   
                        <div class="row g-0 p-3 align-items-center">
                            <h5 class="fw-bold h-font">Car Details</h5>
                            <div class="col-md-12 px-lg-3 px-md-3 px-0">
                               
                                <div class="card-body ">
                                    <table class="table">
                                        <thead class="table-dark text-center " >
                                            <tr>
                                                <th>Attribute</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr>
                                                <td>Fuel Type</td>
                                                <td>$car_data[fuel_type]</td>
                                            </tr>
                                            <tr>
                                                <td>Car Type</td>
                                                <td>$car_data[car_type]</td>
                                            </tr>
                                            <tr>
                                            <td>Mileage</td>
                                                <td>$car_data[mileage] Kmpl</td>
                                            </tr>
                                            <tr>
                                                <td>Model</td>
                                                <td>$car_data[model]</td>
                                            </tr>
                                            

                                            <tr>
                                                <td>Sit</td>
                                                <td>$car_data[sit]</td>
                                            </tr>
            
                                            <tr>
                                            <td>Displacement</td>
                                                <td>$car_data[displacement] cc</td>
                                            </tr>
                                            <tr>
                                                <td>Boot Space Capacity</td>
                                                <td>$car_data[boot_capacity] Liter</td>
                                            </tr>
                                            <tr>
                                                <td>Air Bags</td>
                                                <td>$car_data[air_bags]</td>
                                            </tr>

                                            

                                            <tr>
                                                <td>Fuel Tank Capacity</td>
                                                <td>$car_data[fuel_tank_capacity] Liter</td>
                                            </tr>
                                            <tr>
                                                <td>CNG Tank Capacity</td>
                                                <td>$car_data[cng_capacity] KG</td>
                                            </tr>
                                            <tr>
                                                <td>Atansmission Type</td>
                                                <td>$car_data[transmission_types]</td>
                                            </tr>
    
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                 data;
                // }
                ?>
            </div>

        </div>
    </div>

    <?php
    require("includes/footer.php");
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>