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
    <title>Admin Panel - Bookings Records</title>
    <?php require("includes/links.php"); ?>
</head>

<body class="bg-light">

    <?php require("includes/header.php"); ?>

    <div class="container-fluid " id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden ">
                <h3 class="mb-4 h-font fw-bold">BOOKINGS RECORDS</h3>
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <input type="text" id="search_input" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Search">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover border" style="min-width: 1200px;">
                                <thead>
                                    <tr class="bg-dark text-light  align-middle">
                                        <th scope="col">#</th>
                                        <th scope="col">User Details</th>
                                        <th scope="col">Car Details</th>
                                        <th scope="col">Bookings Details</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">

                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination mt-3" id="table-pagination">
                               
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php require("includes/scripts.php"); ?>
    <script src="scripts/bookings_records.js"></script>
</body>

</html>