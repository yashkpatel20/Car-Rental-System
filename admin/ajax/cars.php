<?php

require("../includes/db_config.php");
require("../includes/essentials.php");
adminLogin();


if (isset($_POST['add_car'])) {

    $features = filteration(json_decode($_POST['features']));
    // $facilities = filteration(json_decode($_POST['facilities']));
    // $specifications = filteration(json_decode($_POST['specifications']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "INSERT INTO `car` (`name`, `company`, `sit`, `price_day`, `price_hour`, `fuel_type`,`model`,`car_type`,`air_bags`,`boot_capacity`,`displacement`,`fuel_tank_capacity`,`cng_capacity`,`transmission_types`,`mileage`,`description`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $values = [$frm_data['name'], $frm_data['company'], $frm_data['sit'], $frm_data['price_day'], $frm_data['price_hour'], $frm_data['fuel_type'],$frm_data['model'],$frm_data['car_type'],$frm_data['air_bags'],$frm_data['boot_capacity'],$frm_data['displacement'],$frm_data['fuel_tank_capacity'],$frm_data['cng_capacity'],$frm_data['transmission_types'],$frm_data['mileage'] , $frm_data['description']];

    if (insert($q1, $values, 'ssiiisssiiiiisis', $con)) { {
            $flag  = 1;
        }
        $car_id = mysqli_insert_id($con);

        $q2 = "INSERT INTO `car_features` (`car_id`, `features_id`) VALUES (?,?)";

        if ($stmt = mysqli_prepare($con, $q2)) {
            foreach ($features as $f) {
                mysqli_stmt_bind_param($stmt, 'ii', $car_id, $f);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $flag = 0;
            die('query cannot be prepared -Insert');
        }

        if ($flag) {
            echo 1;
        } else {
            echo 0;
        }
    }
}


if (isset($_POST['get_all_cars'])) {
    $res = select("SELECT * FROM `car` WHERE `removed` = ?", [0], 'i');
    $i = 1;
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        if ($row['status'] == 1) {
            $status = "<button onclick = 'toggle_status($row[id],0)'  class ='btn btn-dark btn-sm shadow-none'>active</button>";;
        } else {
            $status = "<button onclick = 'toggle_status($row[id],1)'  class = 'btn btn-warning btn-sm shadow-none'>inactive</button>";
        }

        $data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[company]</td>
                <td>$row[sit]</td>
                <td >
                    <span class = 'badge rounded-pill bg-light text-dark'>Price / Day : ₹$row[price_day] </span> <br>
                    <span class = 'badge rounded-pill bg-light text-dark'>Price / Hour : ₹ $row[price_hour] </span>
                </td>
                <td>$row[fuel_type]</td>
                <td>$row[model]</td>
                <td>$row[car_type]</td>
                <td>$row[air_bags]</td>
                <td>$row[boot_capacity] Liter</td>
                <td>$row[displacement] cc</td>
                <td >
                    <span class = 'badge rounded-pill bg-light text-dark'>Fuel (Liter) : $row[fuel_tank_capacity] </span> <br>
                    <span class = 'badge rounded-pill bg-light text-dark'>CNG (KG) : $row[cng_capacity] </span>
                </td>
                <td>$row[transmission_types]</td>
                <td>$row[mileage] Kmpl</td>
                   
            </tr>
            <tr class='align-middle'>
            <td colspan='4'>$status</td>
                <td colspan='9'>
                <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm ' data-bs-toggle='modal' data-bs-target='#edit-car'>
                     <i class='bi bi-pencil-square'></i> Edit
                </button>
                <button type='button' onclick=\"car_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm ' data-bs-toggle='modal' data-bs-target='#car-images'>
                     <i class='bi bi-images'></i> Images
                </button>
                <button type='button' onclick='remove_car($row[id])' class='btn btn-danger shadow-none btn-sm '>
                     <i class='bi bi-trash'></i> 
                </button>
                </td>   
            </tr>
        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['get_car'])) {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `car` WHERE `id` = ?", [$frm_data['get_car']], 'i');
    $res2 = select("SELECT * FROM `car_features` WHERE `car_id` = ?", [$frm_data['get_car']], 'i');
    // $res3 = select("SELECT * FROM `car_specifications` WHERE `id` = ?", [$frm_data['get_car']], 'i');

    $cardata = mysqli_fetch_assoc($res1);
    $features = [];


    if (mysqli_num_rows($res2) > 0) {
        while ($row = mysqli_fetch_assoc($res2)) {
            array_push($features, $row['features_id']);
        }
    }

    $data = ["cardata" => $cardata, "features" => $features];
    $data = json_encode($data);
    echo $data;
}

if (isset($_POST['edit_car'])) {
    $features = filteration(json_decode($_POST['features']));
    // $facilities = filteration(json_decode($_POST['facilities']));
    // $specifications = filteration(json_decode($_POST['specifications']));

    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `car` SET `name` = ?, `company` = ?, `sit` = ?, `price_day` = ?, `price_hour` = ?, `fuel_type` = ?,`model` = ?,`car_type` = ?,`air_bags` = ?,`boot_capacity` = ?,`displacement` = ?,`fuel_tank_capacity` = ?,`cng_capacity` = ?,`transmission_types` = ?,`mileage` = ?, `description` = ? WHERE `id` = ?";
    $values = [$frm_data['name'], $frm_data['company'], $frm_data['sit'], $frm_data['price_day'], $frm_data['price_hour'], $frm_data['fuel_type'],$frm_data['model'],$frm_data['car_type'],$frm_data['air_bags'],$frm_data['boot_capacity'],$frm_data['displacement'],$frm_data['fuel_tank_capacity'],$frm_data['cng_capacity'],$frm_data['transmission_types'],$frm_data['mileage'], $frm_data['description'], $frm_data['car_id']];

    if (update($q1, $values, 'ssiiisssiiiiisisi')) {
        $flag = 1;
    }

    $del_features = delete("DELETE FROM `car_features` WHERE `car_id` = ?", [$frm_data['car_id']], 'i');
    // $del_specifications = delete("DELETE FROM `car_specifications` WHERE `car_id` = ?", [$frm_data['car_id']], 'i');

    if (!($del_features)) {
        $flag = 0;
    }

    $q2 = "INSERT INTO `car_features`(`car_id`, `features_id`) VALUES (?,?)";

    if ($stmt = mysqli_prepare($con, $q2)) {
        foreach ($features as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['car_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        $flag = 1;
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('query cannot be prepared -Insert');
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `car` SET `status` = ? WHERE `id` = ?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];;

    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['add_image'])) {
    $frm_data = filteration($_POST);

    $img_r = uploadImage($_FILES['image'], CAR_FOLDER);
    if ($img_r == 'inv_img') {
        echo $img_r;
    } elseif ($img_r == 'inv_size') {
        echo $img_r;
    } elseif ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `car_image`(`car_id`, `image`) VALUES (?,?)";
        $values = [$frm_data['car_id'], $img_r];
        $res = insert($q, $values, "is");
        echo $res;
    }
}

if (isset($_POST['get_car_images'])) {
    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `car_image` WHERE `car_id` = ?", [$frm_data['get_car_images']], 'i');

    $path = CAR_IMG_PATH;

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['thumb'] == 1) {
            $thumb_btn  = "<i class='bi bi-check2 text-light bg-success px-2 py-1 rounded fs-5'></i>";
        } else {
            $thumb_btn  = "<button onclick = 'thumb_image($row[sr_id],$row[car_id])'  class = 'btn btn-secondary shadow-none'>
            <i class='bi bi-check2'></i>
          </button>";
        }
        echo <<<data
            <tr class="align-middle">
                 <td><img src ='$path$row[image]' class = 'img-fluid'> </td>
                 <td>$thumb_btn</td>
                 <td>
                  <button onclick = 'rem_image($row[sr_id],$row[car_id])' class = 'btn btn-danger shadow-none'>
                    <i class='bi bi-trash'></i> 
                  </button>
                 </td>
            </tr>
        data;
    }
}

if (isset($_POST['rem_image'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['image_id'], $frm_data['car_id']];

    $pre_q = "SELECT * FROM `car_image` WHERE `sr_id`=? AND `car_id` = ?";
    $res = select($pre_q, $values, "ii");
    $img = mysqli_fetch_assoc($res);

    if (deleteImage($img['image'], CAR_FOLDER)) {
        $q = "DELETE FROM `car_image` WHERE `sr_id`=? AND `car_id` = ?";
        $res = delete($q, $values, 'ii');
        echo $res;
    } else {
        echo 0;
    }
}


if (isset($_POST['thumb_image'])) {
    $frm_data = filteration($_POST);
    $pre_q = "UPDATE `car_image` SET `thumb` = ? WHERE `car_id` = ?";
    $pre_v = [0, $frm_data['car_id']];
    $pre_res = update($pre_q, $pre_v, 'ii');

    $q = "UPDATE `car_image` SET `thumb` = ? WHERE `sr_id` = ? AND `car_id` = ?";
    $v = [1,$frm_data['image_id'], $frm_data['car_id']];
    $res = update($q, $v, 'iii');

    echo $res;
}

if (isset($_POST['remove_car'])) {
    $frm_data = filteration($_POST);

    $res1 = select("SELECT * FROM `car_image` WHERE `car_id` = ?", [$frm_data['car_id']], 'i');

    while($row = mysqli_fetch_assoc($res1)){
        deleteImage($row['image'], CAR_FOLDER);
    }

    $res2 = delete("DELETE FROM `car_image` WHERE `car_id` = ?", [$frm_data['car_id']], 'i');
    $res3 = delete("DELETE FROM `car_features` WHERE `car_id` = ?", [$frm_data['car_id']], 'i');
    
    $res4 = update("UPDATE `car` SET `removed` = ? WHERE `id` = ?", [1 , $frm_data['car_id']], 'ii');
    
    
    if($res2 || $res3 || $res4){
        echo 1;
    }else{
        echo 0;
    }
}
