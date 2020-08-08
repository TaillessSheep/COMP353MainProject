<?php
require 'config.php';
// Define variables and initialize with empty values
$old_accountID = $new_accountID=$old_password = $new_password = $new_category="";
$old_accountID_err = $new_accountID_err = $old_password_err = $new_password_err = $new_category_err="";
$update_result="";
session_start();
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    // Username modification
    if(isset($_POST['old_accountID']))
    {
        if(empty(trim($_POST["new_accountID"])))
        {
            $new_accountID_err = "Please enter a new username.";
        } else
        {
            // Verify if new_accountID is taken already
            $sql = "SELECT accountID FROM 1Account WHERE accountID = ?";

            if($stmt = mysqli_prepare($db, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_new_accountID);

                // Set parameters
                $param_new_accountID = trim($_POST["new_accountID"]);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_store_result($stmt);
                    //Username taken
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $new_accountID_err = "This ID is already taken.";
                    }
                    //Update the username
                    else
                    {
                        $sql = "UPDATE 1Account SET accountID = ? WHERE accountID = ?";
                        if($stmt = mysqli_prepare($db, $sql))
                        {
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt, "ss", $param_new_accountID,$param_old_accountID);

                            // Set parameters
                            $param_new_accountID = trim($_POST["new_accountID"]);
                            $param_old_accountID = trim($_POST["old_accountID"]);

                            // Attempt to execute the prepared statement
                            if(mysqli_stmt_execute($stmt))
                            {
                                $update_result = "Your ID has been successfully changed!";
                            }
                            else
                            {
                                $old_accountID_err="Please verify your information";
                            }
                        }
                    }
                }
                else
                {

                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Password modification
    if(isset($_POST['old_password']))
    {
        if(empty(trim($_POST["new_password"])))
        {
            $new_password_err = "Please enter a new password.";
        }
        else
        {
            //Update the password
            $sql = "UPDATE 1Account SET password = ? WHERE accountID = ? AND password = ?";
            if($stmt = mysqli_prepare($db, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_new_password, $param_accountID, $param_old_password);

                // Set parameters
                $param_new_password = trim($_POST["new_password"]);
                $param_accountID = trim($_SESSION["accountID"]);
                $param_old_password = trim($_POST["old_password"]);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    $update_result = "Your password has been successfully changed!";
                }
                else
                {
                    $old_password_err="Please verify your information";
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
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
<?php require 'admin_dashboard_navbar.php' //nav bar
?>
<h2 style=" padding-left: 25px;" >Modify Profile</h2>
<div class="wrapper" style="width: 20%; padding-left: 25px;" >
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <h3>Change Login details</h3>
        <div class="form-group <?php echo (!empty($accountID_err)) ? 'has-error' : ''; ?>">
            <label>Old ID</label>
            <input type="text" name="old_accountID" class="form-control" value="<?php echo $old_accountID; ?>">
            <span class="help-block"><?php echo $old_accountID_err; ?></span>
            <label>New ID</label>
            <input type="text" name="new_accountID" class="form-control" value="<?php echo $new_accountID; ?>">
            <span class="help-block"><?php echo $new_accountID_err; ?></span>
        </div>
        <br>
        <h3>Change Password</h3>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Old password</label>
            <input type="password" name="old_password" class="form-control" value="<?php echo $old_password; ?>">
            <span class="help-block"><?php echo $old_password_err; ?></span>
            <label>New password</label>
            <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
            <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>

        <br>
        <span class="help-block" style="color: green"><?php echo $update_result; ?></span>
        <br>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>
</div>
</BODY>
</HTML>
