<?php 
include('../core/config.php');
include('../core/db.php');
include('../core/functions.php');

# if do not have userID and isLoggedIn Cookies, redirect user to index.php
if(!isset($_COOKIE['userID']) && !isset($_COOKIE['isLoggedIn'])){
    header("Location: " . SITE_URL);
} else {   
    # with cookie for userID and isLoggedIn, retrieve info. 
    $userQuery = DB::query("SELECT * FROM user WHERE userID=%i", $_COOKIE['userID']);
    foreach($userQuery as $userResult){
        $userPermission = $userResult['userPermission'];
        $userName = $userResult["userName"];
        $userImage = $userResult["userImage"];
        
        # User w/o permission 1 (admin) will rediect back to index.php
        if($userPermission != 1){
            header("Location: " . SITE_URL . "index.php"); 
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../templates/meta.php'; ?>
    <title>Users List</title>
    <?php include '../templates/styles.php'; ?>
</head>

<body>

    
    <div class="row align-items-stretch d-flex">
        <!-- start of left side navbar -->
        <div class="col-lg-2 col-md-3 col-sm-3 d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" >
            <a href="home.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <svg class="bi me-2" width="20" height="32">
                    <use xlink:href="#bootstrap" />
                </svg>
                <span class="fs-4">Admin Page</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item text-white">
                    <a href="home.php" class="nav-link text-white" aria-current="page">
                        <svg class="bi me-2" width="8" height="16">
                            <use xlink:href="#home" />
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="user-list.php" class="nav-link text-white active">
                        <svg class="bi me-2" width="8" height="16">
                            <use xlink:href="#table" />
                        </svg>
                        Users
                    </a>
                </li>
                <li>
                    <a href="recipe-list.php" class="nav-link text-white">
                        <svg class="bi me-2" width="8" height="16">
                            <use xlink:href="#grid" />
                        </svg>
                        Recipes
                    </a>
                </li>
                <li>
                        <a href="../logout.php" class="nav-link text-white">
                            <svg class="bi me-2" width="4" height="16">
                                <use xlink:href="#grid" />
                            </svg>
                            <button class="btn btn-light">Logout</button>
                        </a>
                    </li>
            </ul>
            <hr>
            <?php include 'admin-templates/sidebar-dropdown.php'; ?>
        </div>
        <!-- start of left side navbar -->

        <!-- start of user's table -->
        <div class="container-fluid col-lg-8 col-md-8 col-sm-8 pt-4 ">
            <table class="table display" id="allUsers">
                <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Profile Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Permission</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // extract user data from DB and display in table format using DATATABLE
                    $userQuery = DB::query("SELECT * FROM user");
                    foreach($userQuery as $userResult){
                        $usingID = $userResult['userID'];
                        echo '<tr class="text-center">';
                            echo '<td scope="row">' . $userResult['userID'] . '</td>';
                            if($userResult['userImage'] == NULL) { echo '<th scope="row"> <img src="https://www.tenforums.com/geek/gars/images/2/types/thumb_15951118880user.png" width="50"></td>'; }  else {  echo '<td scope="row"><img src="../' . $userResult['userImage'] . '" width="50"></td>'; }
                            echo '<td>' . ucwords($userResult['userName']) . '</td>';
                            echo '<td>' . $userResult['userEmail'] . '</td>';
                            # userPermission '1' = Admin, otherwise = User
                            if($userResult['userPermission'] == 1) { echo '<td> Admin </td>'; } else { echo '<td> User </td>'; };
                            
                            echo '<td> 
                                    <a href="../update.php?id=' . $userResult['userID'] .' "> <i class="fas fa-edit"></i> </a>   <br>
                                    <a class="deleteUser" id="'. $usingID .'"><i  class="fas fa-trash-alt"></i></a> 
                                </td>';
                            
                        echo '</tr>';
                    }
                    ?>
                    
                    </tbody>
            </table>
        </div>
    <!-- end of user's table -->
    </div>



    <!-- Javascript -->
    <?php include '../templates/script.php'; ?>

    <!-- Datatable script -->
    <script>
        $(document).ready( function () {
            $('#allUsers').DataTable();
        } );       
    </script>

    <!-- jQuery Ajax -->
    <!-- delete user -->
    <script>
        $(".deleteUser").click(function(){
            var element = $(this);
            var del_id = element.attr("id");
            var info = 'id=' + del_id;
                        
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: "../ajax-userlist-delete.php",
                                data: info,
                                success: function(data){ 
                                    if(data = "Delete"){
                                        Swal.fire(
                                            'Deleted',
                                            'User ID ' + del_id + ' has deleted',
                                            'info'
                                        ).then(function(){
                                            window.location.href = "user-list.php";
                                        })
                                    }
                                }
                            })
                        }
                    });
        });
    </script>

</body>

</html>