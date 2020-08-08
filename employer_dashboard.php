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
<?php require 'employer_dashboard_navbar.php' ;//nav bar
$accountID = $_SESSION['accountID'];
$profileName= $_SESSION['profileName'];
$sql = "SELECT COUNT(*) AS number_jobs FROM 1Job WHERE employerID = '".$_SESSION['accountID']."'";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
$sql2 = "SELECT status FROM `1User` WHERE accountID = '".$_SESSION['accountID']."'";
$result2 = mysqli_query($db,$sql2);
$row2 = mysqli_fetch_array($result2);
?>
<!-- Masthead-->
<header class="masthead" style="height: 69%;">
    <div class="container">
        <div class="masthead-subheading"> <?php echo "Welcome, ". $profileName."."?> </div>
        <br>
        <br>
        <div class="masthead-subheading"> <?php echo "You have ". $row['number_jobs']." jobs posted."?> </div>
        <?php
        if($row2['status']=='frozen')
        {
            ?>
            <div class="masthead-subheading"> <?php echo "Your account is currently frozen. Please update your payment
             options or make a manual payment."?> </div>
            <?php
        }
        ?>
    </div>
</header>

<!-- Contact-->
<section class="page-section" id="contact" style=" width: 100%">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text">Contact Us</h2>
        </div>
        <form id="contactForm" name="sentMessage" method="post">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" name="name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name." required/>
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="email" type="email" placeholder="Your Email *" required="required" data-validation-required-message="Please enter your email address." />
                    </div>
                    <div class="form-group mb-md-0">
                        <input class="form-control" name="phone" type="tel" placeholder="Your Phone *" required="required" data-validation-required-message="Please enter your phone number." />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-group-textarea mb-md-0">
                        <textarea class="form-control" name="message" placeholder="Your Message *" required="required" data-validation-required-message="Please enter a message."></textarea>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div id="success"></div>
                <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">Send Message</button>
            </div>
        </form>
    </div>
</section>
</BODY>
</HTML>