<div class="align-items-center">
    <a href="../userprofile.php" class="align-items-center text-white text-decoration-none">
        <img src="<?php if($userImage == NULL){ echo 'https://www.tenforums.com/geek/gars/images/2/types/thumb_15951118880user.png';} else { echo '../' . $userImage;} ?>" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong> <?php echo ucwords($userName); ?></strong>
    </a>

</div>