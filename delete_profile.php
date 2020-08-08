<?php
require 'config.php'; //TODO UNCOMMENT
// Define variables and initialize with empty values
$delete_confirm = $password = $confirm_password = "";
$delete_confirm_err = $password_err = $confirm_password_err = "";
$update_result="";
session_start();
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
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
    // Validate delete confirmation
    if (empty(trim($_POST["delete_confirm"])))
    {
        $delete_confirm_err = "Please confirm that you want to delete your account.";
    }

    // Check input errors before inserting in database
    if (empty($delete_confirm_err) && empty($password_err) && empty($confirm_password_err))
    {
        // Prepare an delete statement
        $sql = "DELETE FROM 1Account WHERE accountID= ? AND password = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_accountID, $param_password);

            // Set parameters
            $param_accountID = trim($_SESSION["accountID"]);
            $param_password = $password;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: deleted_account.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</HEAD>

<BODY>
<?php require 'user_dashboard_navbar.php' //nav bar
?>

<h2 style="color:red;padding-left: 25px;" >Delete Profile</h2>
<div class="wrapper" style="width: 20%; padding-left: 25px;" >
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <br>
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
        <div class="form-group <?php echo (!empty($delete_confirm_err)) ? 'has-error' : ''; ?>" style="width: 150%">
            <input type="radio" id="delete_confirm" name="delete_confirm" value="delete_confirm">
            <label for="confirm">Confirm account deletion. This action is permanent. </label>
            <span class="help-block"><?php echo $delete_confirm_err; ?></span>
        </div>
        <div class="form-group">
            <input style="color: red" type="submit" class="btn btn-default" value="Delete">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>
</BODY>
</HTML>

