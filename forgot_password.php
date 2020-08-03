<?php
require 'config.php';
// Define variables and initialize with empty values
$new_password = $new_password_confirm= $email = $token ="";
$new_password_err = $new_password_confirm_err= $email_err = $token_err ="";
$query_result="";

// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();
    //Send token email
    if (isset($_POST['sendEmail']))
    {
        if (empty(trim($_POST["email"])))
        {
            $email_err = "Please enter an email address.";
        } else
        {
            $email = trim($_POST['email']);
            $sql = "SELECT accountID,email FROM 1User WHERE email = '$email'";
            $result = mysqli_query($db, $sql);
            if($result != false)
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
                    $txt = "<H2> Your reset account token: <H2><br><p>Please use the following token to reset your password:</p>
                            <p><h3>" . $token . "</h3></p>";
                    $headers = "From: TheNewIndeed@company.com" . "\r\n";
                    mail($to, $subject, $txt, $headers);
                    $query_result = 'A password reset token has been sent to your email adress.';
                } else
                {
                    $query_result = 'Something went wrong. Please try again later';
                }
            }
        }
    }

    //Verify token and change password
    if (isset($_POST['resetPassword']))
    {

        // Validate password
        if (empty(trim($_POST["password"])))
        {
            $password_err = "Please enter a password.";
        } else
        {
            $password = trim($_POST["password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_err = "Please confirm password.";
        } else
        {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password))
            {
                $confirm_password_err = "Password did not match.";
            }
        }

        //Validate Token
        if (empty(trim($_POST["token"])))
        {
            $token_err = "Please enter a token.";
        } else
        {
            $token = trim($_POST["token"]);
        }

        //Validate email
        if (empty(trim($_POST["email2"])))
        {
            $email_err = "Please enter an email address.";
        } else
        {
            $email = trim($_POST['email2']);
            $sql = "SELECT accountID FROM 1User WHERE email = '$email'";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);
            // If result matched an email, table row must be 1 row
            if ($count != 1)
            {
                $email_err = "This email address does not correspond to any account.";
            }
        }

        if(empty($token_err) && empty($email_err) & empty($password_err) & empty($confirm_password_err))
        {
            $sql = "SELECT password_reset_token FROM `1Account` WHERE accountID = '$accountID'";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $db_token=$row['password_reset_token'];
            if($token != $db_token)
            {
                $query_result='Reset token is not valid.';
            }
            else{
                $sql = "UPDATE 1Account SET password = ? WHERE accountID = ?";
                if ($stmt = mysqli_prepare($db, $sql))
                {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_accountID);

                    // Set parameters
                    $param_accountID = $accountID;
                    $param_password = $password;

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt))
                    {
                        //Sucess. Reset password_reset_token
                        $sql = "UPDATE 1Account SET password_reset_token = ? WHERE accountID = ?";
                        if ($stmt = mysqli_prepare($db, $sql))
                        {
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "ss", $param_token, $param_accountID);

                            // Set parameters
                            $param_accountID = $accountID;
                            $param_token = "";

                            // Attempt to execute the prepared statement
                            if (mysqli_stmt_execute($stmt))
                            {
                                $query_result = 'Password was sucessfully changed.';
                            }
                            else
                            {
                                $query_result = 'Something went wrong. Please try again later';
                            }
                        }
                    }
                    else
                    {
                        $query_result = 'Something went wrong. Please try again later';
                    }
                }
            }
        }
    }
}

?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</HEAD>

<BODY>
<h2 style=" padding-left: 25px;" >Reset password</h2>
<div class="wrapper" style="width: 20%; padding-left: 25px;" >
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Obtain reset token</h3>
        <div class="form-group <?php echo (!empty($email)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit" name="sendEmail">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
    <br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Change Password</h3>
        <!-- email-->
        <div class="form-group <?php echo (!empty($email)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email2" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <!-- token-->
        <div class="form-group <?php echo (!empty($token)) ? 'has-error' : ''; ?>">
            <label>Token</label>
            <input type="text" name="token" class="form-control" value="">
            <span class="help-block"><?php echo $token_err; ?></span>
        </div>
        <!-- password-->
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <!-- confirm password-->
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php $confirm_password ='';
            echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit" name="resetPassword">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>
</BODY>
</HTML>
