<?php
require 'config.php';
// Define variables and initialize with empty values
$old_accountID = $new_accountID=$old_password = $new_password = $new_category="";
$old_accountID_err = $new_accountID_err = $old_password_err = $new_password_err = $new_category_err="";
$update_result="";

// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    session_start();

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
                    echo "Oops! Something went wrong. Please try again later.";
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
                $param_userID = trim($_SESSION["accountID"]);
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

    // Category modification
    if(isset($_POST['new_category']))
    {
        if(empty(trim($_POST["new_category"])))
        {
            $new_category_err = "Please select a category.";
        }
        elseif(empty(trim($_POST["category_confirm"])))
        {
            $new_category_err="Please confirm your new category choice";
        }
        //Check if this new category is legal with their # of current applications
        elseif(trim($_POST["new_category"]) == 'basic' || trim($_POST["new_category"])=='prime')
        {
            $sql = "SELECT COUNT(*) AS total_applications FROM `1Applied` WHERE jobSeekerID = '".$_SESSION['accountID']."'";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result);
            if(trim($_POST["new_category"]) == 'basic' && $row['total_applications']>0)
            {
                $new_category_err="You may not change your account to basic as you have open job applications.";
            }
            elseif(trim($_POST["new_category"]) == 'prime' && $row['total_applications']>5)
            {
                $new_category_err="You may not change your account to prime as you have more than 5 open job applications.";
            }
        }
        if(empty($new_category_err))
        {
            //Update the category
            $sql = "UPDATE 1User SET premiumOpt = ? WHERE accountID = ? ";
            if($stmt = mysqli_prepare($db, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_new_category, $param_accountID);

                // Set parameters
                $param_new_category = trim($_POST["new_category"]);
                $param_accountID = trim($_SESSION["accountID"]);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    $update_result = "Your user category has been successfully changed!";
                }
                else
                {
                    $new_category_err="Please verify your information";
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
<?php require 'user_dashboard_navbar.php' //nav bar
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
        <h3>Change Account Category
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><i class="material-icons">info</i></button>
        </h3>

        <br>
        <div class="form-group <?php echo (!empty($new_category_err)) ? 'has-error' : ''; ?>">
            <label>Account Type:   </label>
            <select name="new_category" size="1">
                <option value="" selected disabled hidden>Choose Account Type</option>
                <option value="basic">Basic (Free!) </option>
                <option value="prime">Prime (10$/Month)</option>
                <option value="gold">Gold (20$/Month)</option>
            </select>
            <label for="confirm">Confirm Category Change? </label>
            <input type="radio" id="category_confirm" name="category_confirm" value="category_confirm">
            <span class="help-block"><?php echo $new_category_err; ?></span>
        </div>
        <span class="help-block" style="color: green"><?php echo $update_result; ?></span>
        <br>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Account Category Details</h4>
            </div>
            <div class="modal-body">
                <p>Basic: You may view as many jobs as you wish, but you cannot apply. Fees: Free!</p>
                <p>Prime: You may view as many jobs as you wish and apply for up to 5 jobs. Fees: 10$ monthly.</p>
                <p>Gold (Recommended): You may view and apply to as many jobs as you wish! Fees: 20$ monthly.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
</BODY>
</HTML>
