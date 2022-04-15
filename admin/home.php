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
    <title>Document</title>
    <?php include '../templates/styles.php'; ?>
</head>

<body>
    <div class="row  align-items-stretch d-flex">
        <!-- start of left side navbar -->
        <div class="col-lg-2 col-md-3 col-sm-3 d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="height: 100vh;">
            <a href="home.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <svg class="bi me-2" width="20" height="32">
                    <use xlink:href="#bootstrap" />
                </svg>
                <span class="fs-4">Admin Page</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="home.php" class="nav-link text-white active" aria-current="page">
                        <svg class="bi me-2" width="8" height="16">
                            <use xlink:href="#home" />
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="user-list.php" class="nav-link text-white">
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

        <!-- start of main content -->
        <div class="container-fluid col-6 text-center mt-2">
            <div class="row">
                <!-- number of active users -->
                <div class="col-sm-6 mt-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <i class="far fa-user fa-2x"></i>
                            <h5 class="card-title">Number of Active Users</h5>
                            <p class="card-text">
                                <!-- using count() to count those userPermission whom are not 1 (user) -->
                                <?php 
                                    $userQuery = DB::query("SELECT * FROM user WHERE userPermission != 1");
                                    $userCount = DB::count();
                                    
                                    echo '<h1>' . $userCount . '</h1>'
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- number of active admin -->
                <div class="col-sm-6 mt-3">
                    <div class="card bg-info">
                        <div class="card-body">
                            <i class="far fa-user fa-2x"></i>
                            <h5 class="card-title">Number of Active Admin </h5>
                            <p class="card-text">
                                <!-- using count() to count those userPermission whom are 1 (admin) -->
                                <?php 
                                    $userQuery = DB::query("SELECT * FROM user WHERE userPermission = 1");
                                    $userCount = DB::count();
                                    
                                    echo '<h1>' . $userCount . ' </h1>'
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- number of recipe -->
                <div class="col-sm-6 mt-3">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <i class="fas fa-utensils fa-2x"></i>
                            <h5 class="card-title">Number of Recipes</h5>
                            <p class="card-text">
                                <!-- using count() to calculate how many recipeID are there -->
                                <?php 
                                    $recipeQuery = DB::query("SELECT * FROM recipe WHERE recipeID != 0");
                                    $recipeCount = DB::count();
                                    
                                    echo '<h1>' . $recipeCount . ' </h1>'
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of main content -->
    </div>

    <!-- Javascript -->
    <?php include '../templates/script.php'; ?>
    
</body>

</html>