<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Bootstrap JS (including Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">

<!-- Your other script tags -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>


<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>

 -->
<link rel="stylesheet" href="assets/css/common.css">

<?php

    session_start();
    date_default_timezone_set('Asia/Kolkata');
    require('admin/includes/db_config.php');
    require('admin/includes/essentials.php');

    $setting_q = "SELECT * FROM `settings` WHERE `sr_no` = ?";
    $values = [1];

    $setting_r = mysqli_fetch_assoc(select($setting_q, $values, 'i'));

    if($setting_r['shutdown']){
        echo <<<alertbar
            <div class="bg-danger text-center text-white h-font fw-bold p-2">
                 <i class="bi bi-exclamation-triangle-fill"></i>
                 Booking Are Temporarily Closed!
            </div>
        alertbar;
    }

?>