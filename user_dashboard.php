<?php
require 'config.php';
session_start();
?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

    <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</HEAD>
<BODY>
<?php require 'user_dashboard_navbar.php' ;//nav bar
$accountID = $_SESSION['accountID'];
$profileName= $_SESSION['profileName'];
$sql = "SELECT COUNT(*) AS number_jobs FROM `1Applied` WHERE jobSeekerID = '".$_SESSION['accountID']."';";
$result = mysqli_query($db,$sql);
if($result==false){echo $db->error;}
$row = mysqli_fetch_array($result)
?>
<!-- Masthead-->
<header class="masthead" style="height: 69%;">
    <div class="container">
        <div class="masthead-subheading"> <?php echo "Welcome ". $profileName."."?> </div>
        <br>
        <br>
        <div class="masthead-subheading"> <?php echo "You have applied to ". $row['number_jobs']." job(s)."?> </div>
    </div>
</header>

<!-- Map image style-->
<section class="page-section" id="contact" style=" width: 100%; height: 29%">
    <div class="container">
    </div>
</section>