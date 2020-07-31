<?php
// **THIS FILE IS NOT GOOD**
// Include config file
require "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if( isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST"){
    session_start();
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT employerID FROM dummyEmployerTable WHERE username = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    }
    else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    //Validate payment info
    if(empty(trim($_POST["payment_info"]))){
        $payment_info_err = "Please enter your payment information";
    } else{
        $payment_info = trim($_POST["payment_info"]);
    }

    //Validate account category info
    if(empty(trim($_POST["account_type"]))){
        $account_type_err = "Please choose an option";
    } else{
        $account_type = trim($_POST["account_type"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) &&
        empty($payment_info_err) && empty($account_type_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO dummyEmployerTable (username, password, category, creditCardNumber) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password,
                $param_account_type,$param_payment_info);

            // Set parameters
            $param_username = $username;
            $param_password = $password;
            $param_account_type = $account_type;
            $param_payment_info =$payment_info;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                $_SESSION['username']=$username;
                header("location: employer_dashboard.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Employer Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php $confirm_password ='';
            echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($payment_info_err)) ? 'has-error' : ''; ?>">
            <label>Credit Card number:   </label>
            <input type="text" name="payment_info" class="form-control" value="<?php echo $payment_info=''; ?>">
            <span class="help-block"><?php echo $payment_info_err; ?></span>
        </div>

        <br>
        <div class="form-group <?php echo (!empty($account_type_err)) ? 'has-error' : ''; ?>">
            <label>Account Type:   </label>
            <select name="account_type" size="1">
                <option value="" selected disabled hidden>Choose Account Type</option>
                <option value="prime">Prime (50$/Month)</option>
                <option value="gold">Gold (100$/Month)</option>
            </select>
            <span class="help-block"><?php echo $account_type_err; ?></span>
        </div>

        <br>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>

        <p>Already have an account? <a href="employer_login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
