<?php
require 'config.php';
// Define variables and initialize with empty values
$password = $confirm_password= $email = $token ="";
$password_err = $confirm_password_err= $email_err = $token_err =$token_err2="";
$query_result=$query_result2="";

// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();

        if (empty(trim($_POST["email"])))
        {
            $email_err = "Please enter an email address.";
        } else
        {
            $email = trim($_POST['email']);
            $sql = "SELECT accountID,email FROM 1User WHERE email = '$email'";
            $result = mysqli_query($db, $sql);
            if ($result != false)
            {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $count = mysqli_num_rows($result);
            }

            // If result matched an email, table row must be 1 row
            if ($count != 1)
            {
                $email_err = "This email address does not correspond to any account.";
            }
        }

        if (empty($email_err))
        {
            try
            {
                $token = bin2hex(random_bytes(10));
            } catch (Exception $e)
            {
                $query_result = 'Something went wrong. Try again later.';
            }
            $accountID = $row['accountID'];
            $sql = "UPDATE 1Account SET password_reset_token = ? WHERE accountID = ?";
            if ($stmt = mysqli_prepare($db, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_token, $param_accountID);

                // Set parameters
                $param_accountID = $accountID;
                $param_token = $token;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt))
                {
                    $to = $email;
                    $subject = "Password Reset";
                    $txt = "<html><body><H2> Don't worry, even the best of us forget their passwords! <H2><p>Please use the following token to reset your password:</p>
                            <p><h3>" . $token . "</h3></p></body></html>";
                    $headers = "From: TheNewIndeed@company.com" . "\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    mail($to, $subject, $txt, $headers);
                    $query_result = 'A password reset token has been sent to your email adress.';
                } else
                {
                    $query_result = 'Something went wrong. Please try again later';
                }
            }
        }
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
<?php require 'employer_dashboard_navbar.php' ;//nav bar
$accountID = $_SESSION['accountID'];
$profileName= $_SESSION['profileName'];
$sql = "SELECT COUNT(*) AS number_jobs FROM 1Job WHERE employerID=".$accountID;
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result)
?>
<!-- Masthead-->
<header class="masthead" style="height: 69%;">
    <div class="container">
        <div class="masthead-subheading"> <?php echo "Welcome, ". $profileName."."?> </div>
        <br>
        <br>
        <div class="masthead-subheading"> <?php echo "You have ". $row['number_jobs']." jobs posted."?> </div>
    </div>
</header>

<!-- Contact-->
<section class="page-section" id="contact" style=" width: 100%">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text">Contact Us</h2>
        </div>
        <form id="contactForm" name="sentMessage" novalidate="novalidate">
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" name="name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name." />
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