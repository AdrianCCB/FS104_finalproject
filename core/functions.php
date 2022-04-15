<?php

    # validate input value
    function input_validation($value) {
        $value = trim($value);      # Strip unnecessary characters (extra space, tab, newline) from the user input data
        $value = stripslashes($value);      #Remove backslashes (\) from the user input data
        $value = htmlspecialchars($value);      #converts special characters to HTML entities
        return $value;
    }

    function alertRedirect($message, $redirectURL){
        echo "<script language='JavaScript'>window.alert('$message');window.location='$redirectURL'</script>";
    }

    # set Cookies with 30 days expiry
    function setCookies($userID){
        setcookie("userID", $userID, time() + (86400 * 30)); 
        setcookie("isLoggedIn", true, time() + (86400 * 30)); 
    }
    
    # clear Cookies
    function clearCookies(){
        setcookie("userID", "", time() - 3600); 
        setcookie("isLoggedIn", "", time() - 3600);  
    }

    # check if the user has logged in before
    function isLoggedIn(){
        if(isset($_COOKIE['userID']) && isset($_COOKIE['isLoggedIn'])){
          $userQuery = DB::query("SELECT * FROM user WHERE userID=%i", $_COOKIE['userID']);
          $userCount = DB::count();
          if($userCount == 1){
              return true; //is logged in
          } else {
              return false; //is  NOT logged in
          }
        } else {
            return false; //is  NOT logged in
        }
    }

    # upload image
    function uploadImage($name){
        $returnMsg ='';
        $currentDirectory = getcwd() . "/";
        $uploadDirectory = "assests/images/";
    
        $fileExtensionsAllowed = ['jpg', 'jpeg', 'jpe', 'jif', 'jfif', 'jfi', 'png', 'webp', 'bmp', 'dib', 'heif', 'heic', 'svg', 'svgz']; // These will be the only file extensions allowed 
    
        $fileName = $_FILES[$name]['name'];
        $fileSize = $_FILES[$name]['size'];
        $fileTmpName  = $_FILES[$name]['tmp_name'];
        $fileType = $_FILES[$name]['type'];
    
        // Extracting file extension
        $fileExtension = explode('.', $fileName);
        $fileExtension = end($fileExtension);
        $fileExtension = strtolower($fileExtension);
    
        $encryptedFileName = md5(basename($fileName, "." . $fileExtension) . rand()) . "." . $fileExtension;
    
        $uploadPath = $currentDirectory . $uploadDirectory . $encryptedFileName;
    
        if (!in_array($fileExtension, $fileExtensionsAllowed)) {
            $returnMsg = "This file extension is not allowed. Please upload a valid image file";
        }
    
        if ($fileSize > 10000000) {
            $returnMsg = "File exceeds maximum size (10MB)";
        }
    
        if ($returnMsg == "") {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
    
            if ($didUpload) {
                $returnMsg = "The file " . htmlspecialchars(basename($fileName)) . " has been uploaded";
            } else {
                $returnMsg = "An error occurred. Please contact the administrator.";
            }
        } else {
            $didUpload = false;
        }
    
        if($didUpload == true){
            $uploadedFile = $uploadDirectory . $encryptedFileName;
        } else {
            $uploadedFile = "";
        }
    
        return array("uploadedFile" => $uploadedFile, "returnMsg" => $returnMsg);
    }

    # sweet alert pop up
    function alert1($icon, $title, $text, $confirm, $timer, $redirect){
        echo "<script>
            Swal.fire({
                position: 'center',
                icon: '$icon',
                title: '$title',
                text: '$text',
                showConfirmButton: '$confirm',
                timer: $timer
              }).then(function(){
                window.location.href = '$redirect';
            });
            </script>";
    }
    
?>

