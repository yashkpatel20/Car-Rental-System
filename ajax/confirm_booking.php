<?php

require("../admin/includes/db_config.php");
require("../admin/includes/essentials.php");

date_default_timezone_set('Asia/Kolkata');

if(isset($_POST['check_availability'])){
    $frm_data = filteration($_POST);
    $status = "";
    $result = "";

    //bookingin or drop out validation
    
    $today_date = new DateTime(date('Y-m-d'));
    $booking_date  = new DateTime($frm_data['bookingin']);
    $drop_date  = new DateTime($frm_data['dropout']);

    if($booking_date == $drop_date){
       $status = "booking_in_drop_out_same";
       $result = json_encode(["status" => $status]);
    }else if($drop_date < $booking_date){
        $status = "booking_date_past";
        $result = json_encode(["status" => $status]);
    }else if($booking_date < $today_date){
        $status = "dropout_date_past";
        $result = json_encode(["status" => $status]);
    }

    // check if car is available if status is blank else return the error
    if($status!=""){
        header('Content-Type: application/json');
        echo $result;
    }else{
        session_start();
        // check car is available or not
        // $tb_query = "SELECT COUNT(*) AS `total_booking` FROM `booking_orders` WHERE `booking_status`='pending' AND `car_id`=? AND `dropout`> ? AND `bookingin`< ?";
        // $values = ['booked',$_SESSION['car']['id'],$frm_data['bookingin'],$frm_data['dropout']];

        // $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'siss'));

        // if($tb_fetch['total_booking'] > 0){
            // $status = "car_not_available";
            // $result = json_encode(["status" => $status]);
            // header('Content-Type: application/json');
            // echo $result;
            // exit;
        // }
        $count_days = date_diff($booking_date, $drop_date) ->days;
        $payment = $_SESSION['car']['price_day'] * $count_days;
        
        $_SESSION['car']['payment'] = $payment;
        $_SESSION['car']['available'] = true;

        $result = json_encode(["status" => "available","days" => $count_days, "payment" => $payment]);
        header('Content-Type: application/json');
        echo $result;
    }
}
?>
