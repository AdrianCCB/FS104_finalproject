<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

$name = ($_POST['name']);
$email = ($_POST['email']);
$phone = ($_POST['phone']);


if(empty($name) || empty($email) || empty($phone)){ 
    echo "Please kindly fill up all details";
} else { 
    if(preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (preg_match("/^[0-9]{8}$/", $phone)) {
              echo true;  
            } else {
                echo "Phone number must be in 8 digits";
            }
        } else {
            echo "Invalid email format";
        }
    } else {
        echo "Only letters and white space allowed for Name";
    }
}

?>