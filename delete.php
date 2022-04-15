<?php
include('core/config.php');
include('core/db.php');
include('core/functions.php');

#Check if there is a GET parameter of "id"
if(!isset($_GET['id']) || $_GET['id'] == ""){
    #No GET parameter detected
    header('Location: ' . SITE_URL);
} else {
    #GET parameter has value
    #Delete the user
    DB::delete("user", "userID = %i", $_GET['id']);
    #Redirect the user back to dashboard
    header('Location: ' . SITE_URL. 'admin/user-list.php');
}
?>