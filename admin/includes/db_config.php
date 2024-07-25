<?php 
 
 $localhost = 'localhost';
 $username = 'root';
 $password = '';
 $db = 'car_rental_system';

 $con = mysqli_connect($localhost, $username, $password, $db);
 if(!$con){
    die('Cannnot Connect to Database'. mysqli_connect_error());
 }

 function filteration($data){
    foreach($data as $key => $value){
       $value = trim($value);
       $value= stripslashes($value);
       $value = strip_tags($value);
       $value= htmlspecialchars($value);
       $data[$key] = $value;
    }
    return $data;
 }

 function selectAll($table){
    $con = $GLOBALS['con'];
    $res = mysqli_query($con, "SELECT * FROM $table");
    return $res;
 }
 function select($sql , $values ,$datatype){
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql)){
        mysqli_stmt_bind_param($stmt,$datatype, ...$values);
        if(mysqli_stmt_execute($stmt)){
           
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }else{
            mysqli_stmt_close($stmt);
            die('Query Cannot be executed - Select');
        }
       
    }else{
        die('Query Cannot be prepared - Select');
    }
 }

 function update($sql , $values ,$datatype){
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql)){
        mysqli_stmt_bind_param($stmt,$datatype, ...$values);
        if(mysqli_stmt_execute($stmt)){
           
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }else{
            mysqli_stmt_close($stmt);
            die('Query Cannot be executed - Update');
        }
       
    }else{
        die('Query Cannot be prepared - Update');
    }
 }

 function insert($sql , $values ,$datatype){
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql)){
        mysqli_stmt_bind_param($stmt,$datatype, ...$values);
        if(mysqli_stmt_execute($stmt)){
           
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }else{
            mysqli_stmt_close($stmt);
            die('Query Cannot be executed - Insert');
        }
       
    }else{
        die('Query Cannot be prepared - Insert');
    }
 }


 function delete($sql , $values ,$datatype){
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql)){
        mysqli_stmt_bind_param($stmt,$datatype, ...$values);
        if(mysqli_stmt_execute($stmt)){
           
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }else{
            mysqli_stmt_close($stmt);
            die('Query Cannot be executed - Delete');
        }
       
    }else{
        die('Query Cannot be prepared - Delete');
    }
 }
