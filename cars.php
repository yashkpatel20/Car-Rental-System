<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <title>Car Rental - CARS</title>
    <?php
    require("includes/links.php");
    ?>
    <style>

    </style>
</head>

<body class="bg-light">
    <?php
    require("includes/header.php");

    $pickup_date_default = "";
    $drop_date_default = "";
    $fuel_type_default = "";
    $car_type_default = "";
    if (isset($_GET['check_availability'])) {
        $frm_data = filteration($_GET);

        $pickup_date_default = $frm_data['pick_date'];
        $drop_date_default = $frm_data['drop_date'];
        $fuel_type_default = $frm_data['fuel_type'];
        $car_type_default = $frm_data['car_type'];
    }
    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR CARS</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow ">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <div class="border bg-light p-3 rounded mb-3">
                                <h6 class="d-flex align-items-center justify-content-between mb-2" style="font-size: 16px;"><span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn btn-sm text-secondary shadow-none d-none">RESET</button>
                                </h6>
                                <label class="form-label">Pick Up Date</label>
                                <input type="date" class="form-control shadow-none" id="pickup_date" value="<?php echo $pickup_date_default; ?>" onchange="chk_avail_filter()">
                                <label class="form-label mt-2" style="font-weight: 500;">Drop Date</label>
                                <input type="date" class="form-control shadow-none" id="drop_date" value="<?php echo $drop_date_default; ?>" onchange="chk_avail_filter()">
                            </div>

                            <div class="border bg-light p-3 rounded mb-3">
                                <label class="form-label" style="font-weight: 500;">Select Fuel Type</label>
                                
                                <select class="form-select shadow-none" name="fuel_type">
                                <button id="fuel_type_btn" onclick="fuel_type_clear()" class="btn btn-sm text-secondary shadow-none d-none">RESET</button>    
                                <?php
                                    $fuel_type_q = mysqli_query($con, "SELECT DISTINCT `fuel_type` FROM `car`");

                                    while ($fuel_type_res = mysqli_fetch_assoc($fuel_type_q)) {
                                        echo "<option value=\"{$fuel_type_res['fuel_type']}\"";

                                        // Check if the current option is the default one
                                        if ($fuel_type_res['fuel_type'] == $fuel_type_default) {
                                            echo " selected"; // Add 'selected' attribute if it matches the default
                                        }

                                        echo ">{$fuel_type_res['fuel_type']}</option>";
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="border bg-light p-3 rounded mb-3">
                                <label class="form-label" style="font-weight: 500;">Select Car Type</label>
                                <select class="form-select shadow-none" name="car_type">
                                    <?php
                                    $car_type_q = mysqli_query($con, "SELECT DISTINCT `car_type` FROM `car`");

                                    while ($car_type_res = mysqli_fetch_assoc($car_type_q)) {
                                        echo "<option value=\"{$car_type_res['car_type']}\"";

                                        // Check if the current option is the default one
                                        if ($car_type_res['car_type'] == $car_type_default) {
                                            echo " selected"; // Add 'selected' attribute if it matches the default
                                        }

                                        echo ">{$car_type_res['car_type']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>


                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 mb-3 px-3" id="cars-data">

            </div>

        </div>
    </div>


<script>
    let cars_data = document.getElementById('cars-data');
    let pickup_date = document.getElementById('pickup_date');
    let drop_date = document.getElementById('drop_date');
    let chk_avail_btn = document.getElementById('chk_avail_btn');

    function fetch_cars() {
        let fuel_type_btn = document.getElementById('fuel_type_btn'); // Move this line inside the function

        let chk_avail = JSON.stringify({
            pickup_date: pickup_date.value,
            drop_date: drop_date.value
        });

        let fuel_type_list = {
            "fuel_type": []
        };

        let get_fueltype = document.querySelectorAll('[name="fuel_type"]:checked');
        if (get_fueltype.length > 0) {
            get_fueltype.forEach((fuel_type) => {
                fuel_type_list.fuel_type.push(fuel_type.id);
            })
            fuel_type_btn.classList.remove('d-none');
        } else {
            // fuel_type_btn.classList.add('d-none');
        }
        fuel_type_list = JSON.stringify(fuel_type_list);

        let xhr = new XMLHttpRequest();
        xhr.open("GET", "ajax/cars.php?fetch_cars&chk_avail=" + chk_avail + "&fuel_type_list=" + fuel_type_list, true);

        xhr.onprogress = function () {
            cars_data.innerHTML = `
                <div class="spinner-border text-info mb-3 d-block mx-auto " id="info_loader">
                    <span class="visually-hidden">Loading...</span>
                </div>`;
        }
        xhr.onload = function () {
            cars_data.innerHTML = this.responseText;
        }
        xhr.send();
    }

    function chk_avail_filter() {
        if (pickup_date.value != '' && drop_date.value != '') {
            fetch_cars();
            chk_avail_btn.classList.remove('d-none');
        }
    }

    function chk_avail_clear() {
        pickup_date.value = '';
        drop_date.value = '';
        chk_avail_btn.classList.add('d-none');
        fetch_cars();
    }

    function fuel_type_clear() {
        let fuel_type_btn = document.getElementById('fuel_type_btn');
        let get_fueltype = document.querySelectorAll('[name="fuel_type"]:checked');
        if (get_fueltype.length > 0) {
            get_fueltype.forEach((fuel_type) => {
                fuel_type.checked = false;
            })
            fuel_type_btn.classList.add('d-none');
            fetch_cars();
        }
    }

    fetch_cars();
</script>




    <?php
    require("includes/footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>