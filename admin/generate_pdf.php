<?php

require("includes/essentials.php");
require("includes/db_config.php");
require("includes/mpdf/vendor/autoload.php");

adminLogin();


if (isset($_POST['gen_pdf']) && isset($_GET['id'])) {
    $frm_data = filteration($_POST);

    $query = "SELECT bo.*, bd.*, uc.* FROM `booking_order` bo 
    INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
    INNER JOIN `user_cred` uc ON bo.user_id = uc.id
    WHERE ((bo.booking_status = 'booked' AND bo.arrival = 1) OR
    (bo.booking_status = 'cancelled' AND bo.refund = 1 ) OR
    (bo.booking_status = 'payment failed')) AND
    bo.booking_id = '$frm_data[id]' ";

    $res = mysqli_query($con, $query);

    $total_rows = mysqli_num_rows($res);
    if ($total_rows == 0) {
        header("location: dashboard.php");
        exit;
    }

    $data = mysqli_fetch_assoc($res);
    $date = date("h:ia d-m-Y", strtotime($data['datentime']));
    $booking_date = date("d-m-Y", strtotime($data['booking_date']));
    $dropout_date = date("d-m-Y", strtotime($data['dropout_date']));


    $table_data = "
     <h2 class='text-center h-font fw-bold'>BOOKING RECEIPT</h2>
     <table border='1'>
        <tr>
            <td> Order Id : $data[order_id]</td>
            <td> Booking Date : $data</td>
        </tr>
        <tr>
            <td> Status : $data[booking_status]</td>
        </tr>
        <tr>
            <td> Name : $data[user_name]</td>
            <td> Email : $data[email]</td>
        </tr>
        <tr>
            <td> Phone No : $data[phone_no]</td>
            <td> Address : $data[address]</td>
        </tr>
        <tr>
            <td>Car Name : $data[car_name]</td>
            <td>Car Company : $data[car_company]</td>
            <td>Car Price : ₹ $data[price] par day</td>
        </tr>

        <tr>
            <td>Booking Date : $booking_date</td>
            <td>Dropout Date : $dropout_date</td>
        </tr>
    ";

    if ($data['booking_status'] == 'cancelled') {
        $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";

        $table_data .= "<tr>
            <td>Amount Paid : $data[trans_amt]</td>
            <td>Refund : $refund</td>
        </tr>
        ";
    } else if ($data["booking_status"] == 'payment failed') {
        $table_data .= "<tr>
            <td>Transaction Amount : $data[trans_amt]</td>
            <td>Transaction Amount Failed : $data[trans_resp_msg]</td>
        </tr>
        ";
    } else {
        $table_data .= "<tr>
            <td>Car Number : $data[car_no]</td>
            <td>Amount Paid : $data[trans_amt]</td>
        </tr>
        ";
    }

    $table_data .= "</table>";
    $mpdf = new \Mpdf\Mpdf();
    // Write some HTML code:
    $mpdf->WriteHTML($table_data);
    // Output a PDF file directly to the browser
    $mpdf->Output($data['order_id'] .'.pdf', 'D');

} else {
    header("location: dashboard.php");
}
