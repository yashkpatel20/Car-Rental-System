<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <?php
    require("includes/links.php");
    ?>
    <title><?php echo $setting_r['site_title']; ?> -BOOKING</title>
    <style>

    </style>
</head>

<body class="bg-light">
    <?php
    require("includes/header.php");
    if (!(isset($_SESSION['login']) && $_SESSION['login']) == true) {
        redirect("index.php");
    }
    ?>


    <div class="container">
        <div class="row">
            <div class="col-12 my-5  px-4">
                <h2 class="fw-bold h-font ">
                    BOOKINGS
                </h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>

            </div>

            <?php

            $query = "SELECT bo.*, bd.* FROM `booking_order` bo 
                INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id  
                WHERE ((bo.booking_status = 'booked' ) OR
                (bo.booking_status = 'cancelled'  ) OR
                (bo.booking_status = 'payment failed')) AND
                (bo.user_id = ?)
                ORDER BY bo.booking_id DESC";

            $res = select($query, [$_SESSION['uId']], 'i');
            while ($data = mysqli_fetch_assoc($res)) {
                $date = date("d-m-Y", strtotime($data['datentime']));
                $booking_date = date("d-m-Y", strtotime($data['booking_date']));
                $dropout_date = date("d-m-Y", strtotime($data['dropout_date']));

                $status_bg = "";
                $btn =  "";

                if ($data['booking_status'] == 'booked') {
                    $status_bg = "bg-success";

                    if ($data['arrival'] == 1) {
                        $btn = "
                        <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark  btn-sm  shadow-none'>
                            Download PDF
                        </a>
                        
                        ";
                        if ($data['rate_review'] == 0) {
                            $btn .= "
                            <button type='button' onclick='review_car($data[booking_id],$data[car_id])' data-bs-toggle='modal' data-bs-target='#rate_reviewModal'  class='btn btn-dark mt-2 btn-sm  shadow-none ms-2'>Rate & Review</button>
                            ";
                        }
                    } else {
                        $btn = "             
                        <button type='button' onclick='cancel_booking($data[booking_id])' class='btn btn-danger mt-2 btn-sm  shadow-none'>
                         Cancel
                      </button>
                      ";
                    }
                } else if ($data['booking_status'] == 'cancelled') {
                    $status_bg = "bg-danger";
                    if ($data['refund'] == 0) {
                        $btn = "
                        <span class='badge bg-primary'>Refund Pending</span>";
                    } else {
                        $btn = "
                        <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark  btn-sm  shadow-none'>
                            Download PDF
                        </a>
                        ";
                    }
                } else {
                    $status_bg = "bg-warning text-dark";
                    $btn = "
                    <a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark  btn-sm  shadow-none'>
                        Download PDF
                    </a>
                    ";
                }


                echo <<<bookings
                    <div class= "col md-4 px-4 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm">
                            <h5 class="fw-bold">$data[car_name] -$data[car_company]</h5>
                            <p>₹ $data[price] per day</p>
                            <p>
                                <b>Booking Date :</b> $booking_date<br>
                                <b>Dropout Date :</b> $dropout_date<br>
                            </p>
                            <p>
                                <b>Amount :</b> ₹ $data[trans_amt]<br>
                                <b>Order ID :</b> $data[order_id]<br>
                                <b>Date :</b> $date<br>
                            </p>
                            <p>
                                <span class= 'badge $status_bg'>$data[booking_status]</span>
                            </p>
                            $btn
                        </div>
                    </div>
                    
                bookings;
            }
            ?>

        </div>
    </div>

    <?php
    if (isset($_GET['cancel_status'])) {
        alert('success', 'Booking Cancelled');
    } else if (isset($_GET['review_status'])) {
        alert('success', 'Thank you for rating & review!');
    }

    ?>

    <div class="modal fade" id="rate_reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center fw-bold h-font"><i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Rate & Review</h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Excellent</option>
                                <option value="4">Very Good</option>
                                <option value="3">Good</option>
                                <option value="2">Poor</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Review</label>
                            <textarea type="text" name="review" rows="3" class="form-control shadow-none" required>
                        </div>
                        <input type="hidden" name="booking_id" value="">
                        <input type="hidden" name="car_id" value="">
                        <div class="text-end">

                            <button type="submit" class="btn btn-dark btn shadow-none">SUBMIT</button>
                            

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php
    require("includes/footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cancel_booking(id) {
            if (confirm("Are you sure you want to cancel this booking?")) {

                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax/cancel_booking.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.responseText == 1) {
                        window.location.href = "bookings.php?cancel_status=true";

                    } else {
                        alert('error', 'Cancellation Failed');
                    }
                };
                xhr.send('cancel_booking&id=' + id);
            }
        }

        let review_form = document.getElementById('review-form');
        function review_car(bid,cid ) {
            review_form.elements['booking_id'].value = bid;
            review_form.elements['car_id'].value = cid;
        }

        review_form.addEventListener('submit', function(e) {
            e.preventDefault();
            let data = new FormData();
            data.append('review_form', '');
            data.append('rating', review_form.elements['rating'].value);
            data.append('review', review_form.elements['review'].value);
            data.append('booking_id', review_form.elements['booking_id'].value);
            data.append('car_id', review_form.elements['car_id'].value);
            
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/review_car.php', true);
            xhr.onload = function() {
                if (this.responseText == 1) {
                    window.location.href = "bookings.php?review_status=true";
         
                } else {
                    var myModal = document.getElementById('rate_reviewModal');
                    var modal = bootstrap.Modal.getInstance(myModal);
                    modal.hide();
                    
                    alert('error', 'Rating & Review Failed!');
                }
            };
            xhr.send(data);
        })
    </script>

</body>

</html>