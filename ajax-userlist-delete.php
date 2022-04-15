<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

if($_GET['id']){
    DB::delete("user", "userID = %i", $_GET['id']);
    echo "Delete";
}
?>

