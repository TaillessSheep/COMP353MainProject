<?php
require 'config.php';
session_start();
$sentMessageResult="";
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    $EID= $_GET['EID'];
    $sql = "SELECT email FROM 1User WHERE accountID = '$EID'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result) ;
    $employer_email=$row['email'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $message=$_POST['message'];

    $to = $employer_email;
    $subject = "Message from potential applicant";
    $txt = $message;
    $headers = "From: ". $email . "\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    mail($to, $subject, $txt, $headers);
}


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
<div class="container">
    <div class="text-center">
        <h2 class="section-heading text">Contact Employer</h2>
    </div>
    <form id="contactForm" name="sentMessage" method="post">
        <div class="row align-items-stretch mb-5">
            <div class="col-md-6">
                <div class="form-group">
                    <input class="form-control" name="name" type="text" placeholder="Your Name *" required/>
                </div>
                <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="Your Email *" required/>
                </div>
                <div class="form-group mb-md-0">
                    <input class="form-control" name="phone" type="tel" placeholder="Your Phone *"  required/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-group-textarea mb-md-0">
                    <textarea class="form-control" name="message" placeholder="Your Message *" required></textarea>
                </div>
            </div>
        </div>
        <div class="text-center">
            <div id="success"></div>
            <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">Send Message</button>
        </div>
    </form>
    <span style="color: green"><?php echo $sentMessageResult ?></span>
</div>
</BODY>
</HTML>