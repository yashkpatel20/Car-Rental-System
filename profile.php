<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <?php
    require("includes/links.php");
    ?>
    <title><?php echo $setting_r['site_title']; ?> - PROFILE</title>
    <style>

    </style>
</head>

<body class="bg-light">
    <?php
    require("includes/header.php");
    if (!(isset($_SESSION['login']) && $_SESSION['login']) == true) {
        redirect("index.php");
    }


    $u_exist = select("SELECT * FROM `user_cred` WHERE `id` = ? LIMIT 1", [$_SESSION['uId']], "s",);

    if (mysqli_num_rows($u_exist) == 0) {
        redirect("index.php");
    }
    $u_fetch = mysqli_fetch_assoc($u_exist);

    ?>


    <div class="container">
        <div class="row">
            <div class="col-12 my-5  px-4">
                <h2 class="fw-bold h-font ">
                    PROFILE
                </h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">PROFILE</a>
                </div>

            </div>
            <?php
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                $path = USERS_IMG_PATH;
                echo <<<data
                    <div class="bg-white col-6 p-3 p-md-4 rounded shadow ">
                         <div class="text-center">
                               <img src="$path$_SESSION[uPic]" style="width: 100px; height: 100px;" class="rounded-circle me-1">
                               <h5 class="fw-bold h-font mt-3">$u_fetch[name]</h5>
                               <h5 class="fw-bold h-font mt-3">$u_fetch[email]</h5>
                               <h5 class="fw-bold h-font mt-3">Phone No: $u_fetch[phone_no]</h5>
                               <h5 class="fw-bold h-font mt-3">Address: $u_fetch[address]</h5>
                               <h5 class="fw-bold h-font mt-3">Pincode: $u_fetch[pincode]</h5>
                               <h5 class="fw-bold h-font mt-3">Date Of Birth: $u_fetch[dob]</h5> 
                               
                         </div>
                    </div>
                    data;
            }
            ?>

            <div class="col-12 my-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow ">
                    <form id="info-form">

                        <h5 class="mb-3 h-font fw-bold">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input name="name" type="text" value="<?php echo $u_fetch['name']; ?>" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input name="phone_no" type="number" value="<?php echo $u_fetch['phone_no']; ?>" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of birth</label>
                                <input name="dob" type="date" value="<?php echo $u_fetch['dob']; ?>" class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pincode</label>
                                <input name="pincode" type="number" value="<?php echo $u_fetch['pincode']; ?>" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $u_fetch['address']; ?> </textarea>
                            </div>


                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none ">Update Profile</button>

                    </form>

                </div>

            </div>


            <div class="col-md-4 my-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow ">
                    <form id="profile-form">

                        <h5 class="mb-3 h-font fw-bold">Picture</h5>
                        <img src="<?php echo USERS_IMG_PATH . $u_fetch['profile']; ?>" class="img-fluid rounded-3 ">
                        <div class="row">
                            <label class="form-label">Update Profile Picture</label>
                            <input name="profile" type="file" accept=".jpg,.jpeg,.png,.webp" class="mb-4 form-control shadow-none " required>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none ">Update Profile</button>

                    </form>

                </div>

            </div>


            <div class="col-md-8 my-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow ">
                    <form id="password-form">
                        <h5 class="mb-3 h-font fw-bold">Change Password</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input name="new_password" type="password" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input name="confirm_password" type="password" class="form-control shadow-none" required>
                            </div>

                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none ">Update Profile</button>

                    </form>

                </div>

            </div>
        </div>
    </div>


    <?php
    require("includes/footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let info_form = document.getElementById('info-form');
        info_form.addEventListener('submit', function(e) {
            e.preventDefault();
            let data = new FormData();
            data.append('info_form', '');
            data.append('name', info_form.elements['name'].value);
            data.append('phone_no', info_form.elements['phone_no'].value);
            data.append('address', info_form.elements['address'].value);
            data.append('pincode', info_form.elements['pincode'].value);
            data.append('dob', info_form.elements['dob'].value);


            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/profile.php', true);
            xhr.onload = function() {
                if (this.responseText == 'phone_already') {
                    alert('error', 'Phone Is Already Registered!');
                } else if (this.responseText == 0) {
                    alert('error', 'No Changes Made!');
                } else {
                    alert('success', 'Profile Updated!');
                }
            };
            xhr.send(data);

        });

        let profile_form = document.getElementById('profile-form');
        profile_form.addEventListener('submit', function(e) {
            e.preventDefault();
            let data = new FormData();
            data.append('profile_form', '');

            data.append('profile', profile_form.elements['profile'].files[0]);

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/profile.php', true);
            xhr.onload = function() {
                if (this.responseText == 'inv_img') {
                    alert('error', 'Only JPEG, PNG, JPG & WEBP Image Are Allowed!');
                } else if (this.responseText == 'upd_failed') {
                    alert('error', 'Image Upload Failed!');
                } else if (this.responseText == 0) {
                    alert('error', 'Updation Failed!');
                } else {
                    window.location.href = window.location.pathname;
                }
            };
            xhr.send(data);
        })

        let password_form = document.getElementById('password-form');

        password_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let new_password = password_form.elements['new_password'].value;
            let confirm_password = password_form.elements['confirm_password'].value;

            if (new_password != confirm_password) {
                alert('error', 'Password Mismatch!');
                return false;
            }

            let data = new FormData();
            data.append('password_form', '');

            data.append('new_password', new_password);
            data.append('confirm_password', confirm_password);


            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/profile.php', true);

            xhr.onload = function() {
                if (this.responseText == 'mismatch') {
                    alert('error', 'Password Mismatch!');
                } else if (this.responseText == 0) {
                    alert('error', 'Updation Failed!');
                } else {
                    alert('success', 'Password Updated!');
                    password_form.reset();
                }
            };
            xhr.send(data);
        })
    </script>

</body>

</html>