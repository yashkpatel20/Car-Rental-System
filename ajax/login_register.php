<?php

require("../admin/includes/db_config.php");
require("../admin/includes/essentials.php");
require("../includes/sendgrid/sendgrid-php.php");
date_default_timezone_set('Asia/Kolkata');


function send_mail($uemail, $token,$type)
{
    if($type == "email_confirmation"){
        $page= "email_confirm.php";
        $subject = "Account Verification Link";
        $content = "confirm your email";
    }else{
        $page= "index.php";
        $subject = "Password Reset Link";
        $content = "reset your password";
    }
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom(SENDGRID_EMAIL, SENDGRID_NAME);
    $email->setSubject($subject);
   
    $email->addTo($uemail);
    $email->addContent(
        "text/html",
        "Click on the link below to $content:<br>
             <a href='" . SITE_URL . "$page?$type&email=$uemail&token=$token" . "'>CLICK HERE</a>
        "
    );
    $sendgrid = new \SendGrid(SENDGRID_API_KEY);
    
    try{
        $sendgrid->send($email);
        return 1;
    }
    catch(Exception $e){
        return 0;
    }

}

if (isset($_POST['register'])) {
    $data = filteration($_POST);

    // match password and confirm password
    if ($data['password'] != $data['cpassword']) {
        echo 'password_mismatch';
        exit;
    }

    // check if email already exists

    $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phone_no` = ? LIMIT 1", [$data['email'], $data['phone_no']], 'ss');
    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    // Upload profile Pic

    $img = uploadUserImage($_FILES['profile']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }

    // Sent Confirmation Link to User's Email

    $token = bin2hex(random_bytes(16));
    if(!send_mail($data['email'],  $token, "email_confirmation")){
        echo 'mail_failed';
        exit;
    }

    $enc_pass = password_hash($data['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO `user_cred`( `name`, `email`, `address`, `phone_no`, `pincode`, `dob`, `profile`, `password`, `token`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $values = [$data['name'], $data['email'], $data['address'], $data['phone_no'], $data['pincode'], $data['dob'], 
    $img, $enc_pass, $token];

    if(insert($query, $values, 'sssssssss')){
        echo 1;
    }else{
        echo 'ins_failed';
    }
}

if (isset($_POST['login'])) {
    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phone_no` = ? LIMIT 1", [$data['email_mobile'], $data['email_mobile']], 'ss');
    if (mysqli_num_rows($u_exist) == 0) {
        echo 'invalid_email_mobile';
        
    }else{
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if($u_fetch['is_verified'] == 0){
            echo 'not_verified';
        }else if($u_fetch['status'] == 0){
            echo 'inactive';
        }else{
            if(!password_verify($data['password'], $u_fetch['password'])){
                echo 'invalid_pass';
            }else{
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['profile'];
                $_SESSION['uPhone'] = $u_fetch['phone_no'];
                echo 1;
            }
        }
    }
    
       

}

if (isset($_POST['forgot_pass'])) {
    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ?  LIMIT 1", [$data['email']], 's');
    if (mysqli_num_rows($u_exist) == 0) {
        echo 'invalid_email';
    }else{
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if($u_fetch['is_verified'] == 0){
            echo 'not_verified';
        }else if($u_fetch['status'] == 0){
            echo 'inactive';
        }else{
            // send reset link
            $token = bin2hex(random_bytes(16));
            if(!send_mail($data['email'],  $token, "account_recovery")){
                echo 'mail_failed';
            }else{
                
                $date = date('Y-m-d');
                $query = mysqli_query($con,"UPDATE `user_cred` SET `token` = '$token', `t_expire` = '$date' WHERE `id` = '$u_fetch[id]'");

                if($query){
                    echo 1;
                }else{
                    echo 'upd_failed';
                }
            }
        }
    }
}

if (isset($_POST['recover_password'])) {
    $data = filteration($_POST);

    // Hash the new password
    $enc_pass = password_hash($data['password'], PASSWORD_BCRYPT);

    // Update the user's password and clear the token
    $query = "UPDATE `user_cred` SET `password` = ?, `token` = null, `t_expire` = null WHERE `email` = ? AND `token` = ?";
    $values = [$enc_pass, $data['email'], $data['token']];

    if (update($query, $values, 'sss')) {
        echo 1; // Password recovery successful
    } else {
        echo 'failed'; // Password recovery failed
    }
}

