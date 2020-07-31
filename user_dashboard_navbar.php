<?php /* This file purpose is to be included at the top of the of the <BODY> of other files in order to display the navigation bar.
        Necessary style sheet to be included in <HEAD>: <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
       */
?>
<div class="navbar">

    <a href="user_dashboard.php"><i class="material-icons" style="color: white">home</i></a>
    <a href="user_job_portal.php">Search Jobs</a>

    <div class="dropdown">
        <button class="dropbtn">Modify Profile
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="user_modify_profile.php">Update Profile</a>
            <a href="user_delete_profile.php">Delete Profile</a>
        </div>
    </div>
    <a href="premium_selection.php">Premium</a>
    <div class="topnav-right">
        <a href="logout.php">Log out</a>
        <a style="pointer-events: none;"> <?php
            date_default_timezone_set('America/Toronto');
            echo(date('d/m/Y', time()));?>
        </a>
    </div>

</div>
