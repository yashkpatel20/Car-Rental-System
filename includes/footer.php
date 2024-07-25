<?php
$contact_q = "SELECT * FROM `contact_details` WHERE `sr_no` = ? ";
$values = [1];
$contact_r = mysqli_fetch_assoc(select($contact_q, $values, "i"));

?>

<div class="container-fluid bg-white p-4 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <h3 class="h-font fw-bold fs-3 mb-2"><?php echo $setting_r['site_title'] ?></h3>
            <p class="text-secondary"><?php echo $setting_r['site_about'] ?></p>
        </div>
        <div class="col-lg-4">
            <h5 class="mb-3">Link</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br>
            <a href="cars.php" class="d-inline-block mb-2 text-dark text-decoration-none">Cars</a><br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a><br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About Us</a><br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact Us</a>
        </div>
        <div class="col-lg-4">
            <h5 class="mb-3">Follow Us </h5>
            <?php
            if ($contact_r['twitter'] != '') {
                echo <<<data
                    <a href="$contact_r[twitter]" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-twitter me-1"></i> Twitter</span>
                    </a>
                    <br>
                    data;
            }
            ?>
            <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3" target="_blank">
                <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-instagram me-1"></i> Instagram</span>
            </a>
            <br>
            <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3" target="_blank">
                <span class="badge bg-light text-dark fs-6 p-2"> <i class="bi bi-facebook me-1"></i> Facebook</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js " integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM " crossorigin="anonymous "></script>

<script>
    function alert(type, message, position = 'body') {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
        <div class="alert ${bs_class} alert-dismissible fade show " role="alert">
            <strong class="me-3">${message}</strong> 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
        if (position == 'body') {
            document.body.append(element);
            element.classList.add('custom-alert');
        } else {
            document.getElementById(position).appendChild(element);
        }

        setTimeout(remAlert, 3000);
    }

    function remAlert() {
        document.getElementsByClassName('alert')[0].remove();
    }

    function setActive() {
        let navbar = document.getElementById('nav-bar');
        let a_tags = navbar.getElementsByTagName('a');

        for (i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file_name) >= 0) {
                a_tags[i].classList.add('active');
            }
        }
    }

    let register_form = document.getElementById('register-form');
    register_form.addEventListener('submit', (e) => {
        e.preventDefault();

        let data = new FormData();

        data.append('name', register_form.elements['name'].value);
        data.append('email', register_form.elements['email'].value);
        data.append('phone_no', register_form.elements['phone_no'].value);
        data.append('address', register_form.elements['address'].value);
        data.append('pincode', register_form.elements['pincode'].value);
        data.append('dob', register_form.elements['dob'].value);
        data.append('password', register_form.elements['password'].value);
        data.append('cpassword', register_form.elements['cpassword'].value);
        data.append('profile', register_form.elements['profile'].files[0]);
        data.append('register', '');

        var myModal = document.getElementById('registerModel');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/login_register.php', true);
    
        xhr.onload = function() {
            if (this.responseText == 'password_mismatch') {
                alert('error', 'Password Mismatch!');
            } else if (this.responseText == 'email_already') {
                alert('error', 'Email Is Already Registered!');
            } else if (this.responseText == 'phone_already') {
                alert('error', 'Phone Is Already Registered!');
            } else if (this.responseText == 'inv_img') {
                alert('error', 'Only JPEG, PNG, JPG & WEBP Image Are Allowed!');
            } else if (this.responseText == 'upd_failed') {
                alert('error', 'Image Upload Failed!');
            } else if (this.responseText == 'mail_failed') {
                alert('error', 'Cannot Send Confirmation Email! Server Down!');
            } else if (this.responseText == 'ins_failed') {
                alert('error', 'Registration Failed! Server Down!');
            } else {
                alert('success', 'Registered Successful. Confirmation Link Sent To Your Email!');
                register_form.reset();
            }

        };
        xhr.send(data);

    });


    let login_from = document.getElementById('login-form');

    login_from.addEventListener('submit', (e) => {
        e.preventDefault();

        let data = new FormData();

        data.append('email_mobile', login_from.elements['email_mobile'].value);
        data.append('password', login_from.elements['password'].value);

        data.append('login', '');

        var myModal = document.getElementById('loginModel');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/login_register.php', true);

        xhr.onload = function() {
            if (this.responseText == 'invalid_email_mobile') {
                alert('error', 'Invalid Email Or Mobile Number!');
            } else if (this.responseText == 'not_verified') {
                alert('error', 'Email Is Not Verified!');
            } else if (this.responseText == 'inactive') {
                alert('error', 'Account Is Suspended! Please Contact Admin!');
            } else if (this.responseText == 'invalid_pass') {
                alert('error', 'Incorrect Password!');
            } else {
                let fileurl = window.location.href.split('/').pop().split('?').shift();
                if (fileurl == 'car_details.php') {
                    window.location = window.location.href;
                }else{
                    window.location = window.location.pathname;
                }
                
            }
        }
        xhr.send(data);

    });


    let forgot_form = document.getElementById('forgot-form');

    forgot_form.addEventListener('submit', (e) => {
        e.preventDefault();
        let data = new FormData();

        data.append('email', forgot_form.elements['email'].value);
        data.append('forgot_pass', '');

        var myModal = document.getElementById('forgotModel');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/login_register.php', true);
        xhr.onload = function() {
            if (this.responseText == 'invalid_email') {
                alert('error', 'Invalid Email !');
            } else if (this.responseText == 'not_verified') {
                alert('error', 'Email Is Not Verified! Please Contact Admin!');
            } else if (this.responseText == 'inactive') {
                alert('error', 'Account Is Suspended! Please Contact Admin!');
            } else if (this.responseText == 'mail_failed') {
                alert('error', 'Cannot Send  Email! Server Down!');
            } else if (this.responseText == 'upd_failed') {
                alert('error', 'Password Reset Failed! Server Down!');
            } else {
                alert('success', 'Password Reset Link Sent To Your Email!');
                forgot_form.reset();
            }
        }
        xhr.send(data);

    });

    function checkLoginToBook(status,car_id){
        if(status == 1){
            window.location.href = "confirm_booking.php?id="+car_id;
        }else{
            alert('error', 'Please Login First!');
        }
    }
    setActive();
</script>