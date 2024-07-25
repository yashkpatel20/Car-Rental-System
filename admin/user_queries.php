<?php
require("includes/essentials.php");
require("includes/db_config.php");
adminLogin();

if (isset($_GET['seen'])) {
    $frm_data = filteration($_GET);

    if ($frm_data['seen'] == 'all') {
        $q = "UPDATE `user_queries` SET `seen` = ? ";
        $values = [1];
        if (update($q, $values, 'i')) {
            alert('success', 'Marked as  all  Read!');
        } else {
            alert('danger', 'Operation Failed!');
        }
    } else {
        $q = "UPDATE `user_queries` SET `seen` = ?  WHERE `sr_no` =?";
        $values = [1, $frm_data['seen']];
        if (update($q, $values, 'ii')) {
            alert('success', 'Marked as Read!');
        } else {
            alert('danger', 'Operation Failed!');
        }
    }
}

if (isset($_GET['delete'])) {
    $frm_data = filteration($_GET);

    if ($frm_data['delete'] == 'all') {
        $q = "DELETE FROM `user_queries`";
        if (mysqli_query($con, $q)) {
            alert('success', 'All Message Deleted!');
        } else {
            alert('danger', 'Operation Failed!');
        }
    } else {
        $q = "DELETE FROM `user_queries` WHERE `sr_no` =?";
        $values = [$frm_data['delete']];
        if (delete($q, $values, 'i')) {
            alert('success', 'Message Deleted!');
        } else {
            alert('danger', 'Operation Failed!');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Queries</title>
    <?php require("includes/links.php"); ?>
</head>

<body class="bg-light">

    <?php require("includes/header.php"); ?>

    <div class="container-fluid " id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden ">
                <h3 class="mb-4 h-font fw-bold">USERS QUERIES</h3>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-dark rounded-pill btn-sm shadow-none ">
                                <i class="bi bi-check2-all"></i> Mark all read
                            </a>
                            <a href="?delete=all" class="btn btn-danger rounded-pill btn-sm shadow-none ">
                                <i class="bi bi-trash3"></i> Delete all
                            </a>
                        </div>

                        <div class="table-responsive-md " style="height: 500px; overflow-y: scroll ;">
                            <table class="table table-hover border">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" width="20%">Subject</th>
                                        <th scope="col" width="30%">Message</th>
                                        <th scope="col">Date_Time</th>
                                        <th scope="col">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = "SELECT * FROM user_queries ORDER BY `sr_no` DESC";
                                    $data = mysqli_query($con, $q);
                                    $i = 1;

                                    while ($row = mysqli_fetch_assoc($data)) {
                                        $seen = '';
                                        if ($row['seen'] != 1) {
                                            $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary' >Mark as Read</a>";
                                        }
                                        $seen .= "<a href='?delete=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger mt-2' >Delete</a>";
                                        echo <<<query
                                                <tr> 
                                                    <td>$i</td>  
                                                    <td>$row[name]</td> 
                                                    <td>$row[email]</td> 
                                                    <td>$row[subject]</td> 
                                                    <td>$row[message]</td> 
                                                    <td>$row[datentime]</td>
                                                    <td>$seen</td>
                                                </tr>
                                           query;
                                        $i++;
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require("includes/scripts.php"); ?>



</body>

</html>