<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <title>Car Rental - ABOUT US</title>
    <?php
    require("includes/links.php");
    ?>
    <style>
        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>

<body class="bg-light">
    <?php
    // Include the header file
    require("includes/header.php");
    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="mb-3 text-center mt-4">Welcome to Car Rental System - your trusted partner for convenient and
            affordable car rentals.</p>

    </div>


    <div class="container">
        <div class="row justify-content-between algin-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Car Rental System</h3>
                <p>
                    At Car Rental System, we are passionate about making your travel experiences memorable and
                    hassle-free. With a commitment to excellence and a fleet of well-maintained vehicles, we ensure you
                    have the perfect ride for every journey.<br><br>

                    Our team is dedicated to providing you with top-notch customer service, offering expert guidance and
                    support to meet your specific needs. Whether you're planning a weekend getaway, a business trip, or
                    a family vacation, we have the right vehicle for you.<br><br>

                    take pride in our transparency, competitive pricing, and user-friendly online booking platform,
                    making it easy for you to reserve the vehicle of your choice. From compact cars to spacious SUVs, we
                    have a diverse range of options to suit your preferences.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">

                <iframe width="560" height="315" src="https://www.youtube.com/embed/Eq1Hz80GTVc?si=jrjM8Cka1M6DRyK3" class="rounded" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/recent-car-3.jpg" width="100px">
                    <h4 class="mt-3">50+ CARS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/recent-car-3.jpg" width="100px">
                    <h4 class="mt-3">50+ CUSTOMER</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/recent-car-3.jpg" width="100px">
                    <h4 class="mt-3">50+ REVIEWS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/recent-car-3.jpg" width="100px">
                    <h4 class="mt-3">50+ REVIEWS</h4>
                </div>
            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold h-font text-center"> OUR TEAM</h3>

    <div class="container">
        <div class="swiper mySwiper bg-white">
            <div class="swiper-wrapper mb-5">
                <?php
                $about_r = selectAll('team_details');
                $path = ABOUT_IMG_PATH;
                while ($row = mysqli_fetch_assoc($about_r)) {
                    echo <<<data
                            <div class="swiper-slide bg-dark rounded text-center overflow-hidden shadow  ">
                                <img src="$path$row[picture]" class="w-100 img-fluid">
                                <h5 class="mt-3 text-white shadow rounded">$row[name]</h5>
                            </div>
                        data;
                }
                ?>

            </div>
        </div>

    </div>
    <?php
    
    // Include the footer file
    require("includes/footer.php");
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js "></script>
    <script src="assets/js/main.js "></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>
</body>

</html>