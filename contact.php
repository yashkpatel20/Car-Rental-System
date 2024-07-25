<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <title>Car Rental - CONTACT</title>
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

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center  ">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <?php
    $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no` = ? ";
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q, $values, "i"));
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 ">
                    <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>
                    <h5>Address</h5>
                    <a href="<?php echo $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-3"><i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address'] ?></a>
                    <h5 class="mt-4">Call Us</h5>
                    <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-telephone-outbound-fill"></i> + <?php echo $contact_r['pn1'] ?></a>
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
                    <a href="mailto:<?php echo $contact_r['email'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-envelope-fill"></i> <?php echo $contact_r['email'] ?></a>
                    <h5 class="mt-4">Follow Us</h5>
                    <?php
                    if ($contact_r['twitter'] != "") {
                        echo <<<data
                            <a href="$contact_r[twitter]" class="d-inline-block mb-3" target="_blank">
                                <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-twitter me-1"></i> </span>
                            </a>
                        
                            data;
                    }
                    ?>
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-instagram me-1"></i> </span>
                    </a>
               
                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-facebook me-1"></i> </span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 ">

                    <form method="POST">
                        <h5 class="fw-bold h-font">Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Name</label>
                            <input name="name" type="text" class="form-control shadow-none" required>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Email</label>
                            <input name="email" type="email" class="form-control shadow-none" required>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Subject</label>
                            <input name="subject" type="text" class="form-control shadow-none" required>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight:500;">Message</label>
                            <textarea name="message" class="form-control shadow-none" rows="5" style="resize:none;" required></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php 
    if(isset($_POST['send'])){
        $frm_data = filteration($_POST);

        $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES(?,?,?,?)";
        $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];
        $res = insert($q, $values, "ssss");
        if($res){
            alert('success', 'Message Sent!');
        }else{
            alert('danger', 'Something went wrong!');
        }
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
    }       
    ?>

    <?php
    require("includes/footer.php");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>