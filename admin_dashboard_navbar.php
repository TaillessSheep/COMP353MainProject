<?php
require 'config.php';
session_start();
$sql = "SELECT status  FROM `1User` WHERE accountID = '".$_SESSION['accountID']."';";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
if($row['status']=='frozen')
{
    ?>

    <div class="navbar" style="margin: 0">
        <div class="dropdown">
            <button class="dropbtn">Modify Profile
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="user_modify_profile.php">Update Profile</a>
                <a href="delete_profile.php">Delete Profile</a>
            </div>
        </div>
        <a href="method_of_payment.php">Payment</a>
        <div class="topnav-right">
            <a href="logout.php">Log out</a>
            <a style="pointer-events: none;">
                <?php
                date_default_timezone_set('America/Toronto');
                echo(date('d/m/Y', time()));?>
            </a>
        </div>
    </div>
    <?php
}
else
{
    ?>

    <div class="navbar" style="margin: 0">
        <a href="user_dashboard.php"><i class="material-icons" style="color: white">home</i></a>
        <a href="user_job_portal.php">Search Jobs</a>
        <a href=\"user_applied_jobs.php">My Applications</a>
        <div class="dropdown">
            <button class="dropbtn">Modify Profile
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="user_modify_profile.php">Update Profile</a>
                <a href="delete_profile.php">Delete Profile</a>
            </div>
        </div>
        <a href=\"method_of_payment.php">Payment</a>
        <div class="topnav-right">
            <a href="logout.php">Log out</a>
            <a style="pointer-events: none;"> <?php
                date_default_timezone_set('America/Toronto');
                echo(date('d/m/Y', time()));?>
            </a>
        </div>
    </div>
    <?php
}
?>




