<?php
require("includes/essentials.php");
require("includes/db_config.php");
adminLogin();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Cars</title>
    <?php require("includes/links.php"); ?>
</head>

<body class="bg-light">

    <?php require("includes/header.php"); ?>

    <div class="container-fluid " id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden ">
                <h3 class="mb-4 h-font fw-bold">CARS</h3>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm " data-bs-toggle="modal" data-bs-target="#add-car">
                                <i class="bi bi-plus-circle"></i> ADD
                            </button>
                        </div>
                        <div class="table-responsive-lg " style=" height: 500px; overflow-y: scroll ;overflow-x: scroll ;">
                            <table class="table table-hover border table-condensed text-center " style="min-width: 1200px;">
                                <thead>
                                    <tr class="bg-dark text-light  align-middle">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Sit</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Fuel Type</th>
                                        <th scope="col">Model</th>
                                        <th scope="col">Car Type</th>
                                        <th scope="col">Air Bags</th>
                                        <th scope="col">Boot Capacity</th>
                                        <th scope="col">Displacement</th>
                                        <th scope="col">Fuel Tank Capacity</th>
                                        <th scope="col">Transmission</th>
                                        <th scope="col">Mileage</th>            
                                        
                                    </tr>
                                </thead>
                                <tbody id="car-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Car Modal -->
    <div class="modal fade" id="add-car" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_car_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Car</h5>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Company</label>
                                <input type="text" name="company" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sit</label>
                                <input type="number" min="5" name="sit" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price Day</label>
                                <input type="number" min="1" name="price_day" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price Hour</label>
                                <input type="number" min="1" name="price_hour" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fuel Type</label>
                                <input type="text" name="fuel_type" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Model</label>
                                <input type="text" name="model" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Car Type</label>
                                <input type="text" name="car_type" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Air Bags</label>
                                <input type="number" name="air_bags" class="form-control shadow-none" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Boot Capacity</label>
                                <input type="number" name="boot_capacity" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Displacement</label>
                                <input type="number" name="displacement" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fuel Tank Capacity</label>
                                <input type="number" name="fuel_tank_capacity" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">CNG Capacity</label>
                                <input type="number" name="cng_capacity" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Transmission</label>
                                <input type="text" name="transmission_types" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mileage</label>
                                <input type="number" name="mileage" class="form-control shadow-none" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Features</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll("features");
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                        <div class='col-md-3 mb-1'>
                                            <label class='checkbox form-check-label'>
                                                <input type='checkbox' name='features' value='$opt[id]' id='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                        ";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" rows="4" class="form-control shadow-none" required></textarea>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Car Modal -->

    <div class="modal fade" id="edit-car" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_car_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Car</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Company</label>
                                <input type="text" name="company" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sit</label>
                                <input type="number" min="2" name="sit" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price Day</label>
                                <input type="number" min="1" name="price_day" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price Hour</label>
                                <input type="number" min="1" name="price_hour" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fuel Type</label>
                                <input type="text" name="fuel_type" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Model</label>
                                <input type="text" name="model" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Car Type</label>
                                <input type="text" name="car_type" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Air Bags</label>
                                <input type="number" name="air_bags" class="form-control shadow-none" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Boot Capacity</label>
                                <input type="number" name="boot_capacity" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Displacement</label>
                                <input type="number" name="displacement" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fuel Tank Capacity</label>
                                <input type="number" name="fuel_tank_capacity" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">CNG Capacity</label>
                                <input type="number" name="cng_capacity" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Transmission</label>
                                <input type="text" name="transmission_types" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mileage</label>
                                <input type="number" name="mileage" class="form-control shadow-none" required>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Features</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll("features");
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                        <div class='col-md-3 mb-1'>
                                            <label class='checkbox form-check-label'>
                                                <input type='checkbox' name='features' value='$opt[id]' id='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                        ";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" rows="4" class="form-control shadow-none" required></textarea>
                            </div>

                            <input type="hidden" name="car_id" class="form-control shadow-none">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Car Images -->


    <div class="modal fade" id="car-images" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h-font fw-bold">Car Name</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="image-alert">

                </div>
                <div class="modal-body">
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold">Add Image</label>
                            <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none mb-3" required>
                            <button class="btn custom-bg text-white shadow-none">ADD</button>
                            <input type="hidden" name="car_id">
                        </form>
                    </div>
                    <div class="table-responsive-lg " style="height: 350px; overflow-y: scroll ;">
                        <table class="table table-hover border text-center">
                            <thead>
                                <tr class="bg-dark text-light sticky-top">
                                    <th scope="col" width="60%">Image</th>
                                    <th scope="col">Thumb</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="car-image-data">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php require("includes/scripts.php"); ?>
    <script src="scripts/cars.js"></script>                                       
</body>

</html>