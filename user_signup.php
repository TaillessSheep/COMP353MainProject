<?php
// Include config file
require "config.php"; //TODO UNCOMMENT

// Define variables and initialize with empty values
$accountID = $password = $confirm_password = $payment_info = $realname =$phone = $email= $MOP= $isAutoPay= $account_type=$selectedMOP='';
$accountID_err = $password_err = $confirm_password_err = $payment_info_err = $realname_err = $phone_err= $email_err= $MOP_err= $isAutoPay_err= $account_type_err="";
$charge="";

// Processing form data when form is submitted
if( isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST"){
    session_start();
    // Validate username
    if(empty(trim($_POST["accountID"]))){
        $accountID_err = "Please enter a username.";
    } else{

        // Prepare a select statement to validate unique accountID
        $sql = "SELECT accountID FROM 1Account WHERE accountID = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_accountID);
            // Set parameters
            $param_accountID = trim($_POST["accountID"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                //if SELECT has found a username, its taken
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $accountID_err = "This ID is already taken.";
                }
                else{
                    //Valid free accountID
                    $accountID = trim($_POST["accountID"]);
                }
            }
            //Failed query execution
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate name
    if(empty(trim($_POST["realname"]))){
        $realname_err = "Please enter a first and last name.";
    }
    else{
        $realname = trim($_POST["realname"]);
    }

    // Validate phone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please phone.";
    }
    else{
        $phone = trim($_POST["phone"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address";
    }
    else{
        $email = trim($_POST["email"]);
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

//    //Validate MOP
//    if(empty(trim($_POST["MOP"]))){
//        $MOP_err = "Please choose a method of payment";
//    } else{
//        $MOP = trim($_POST["MOP"]);
//    }

//    //Validate automatic payment
//    if(isset($_POST['isAutoPay']))
//    {
//        $isAutoPay=1;
//        $selectedMOP=0;
//    }
//    else
//    {
//        $isAutoPay=0;
//    }

    //Get date
    $date = new DateTime('now');
    $date->modify('first day of next month');
    $str_date=date_format($date, 'Y-m-d');


    //Validate account category info
//    if(empty(trim($_POST["account_type"]))){
//        $account_type_err = "Please choose an option";
//    } else{
//        $account_type = trim($_POST["account_type"]);
//        switch ($account_type) {
//            case "basic":
//                $charge=0;
//                break;
//            case "prime":
//                $charge=10;
//                break;
//            case "gold":
//                $charge=20;
//                break;
//        }
//    }

    // Check input errors before inserting in database
    echo 'hah1';
    if(empty($accountID_err) && empty($password_err) && empty($confirm_password_err))
//        &&
//        empty($payment_info_err) && empty($account_type_err))
    {
        // Prepare an insert statement in account table
        $sql = "INSERT INTO 1Account (accountID, password,profileName) VALUES (?, ?, ?)";
        echo 'heh1';
        if($stmt = mysqli_prepare($db, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_accountID, $param_password,$param_realname);

            // Set parameters
            $param_accountID = $accountID;
            $param_password = $password;
            $param_realname = $realname;

            echo 'heh1';
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                //Insertion in account was successfull. Prepare insert statement in Users
                $sql = "INSERT INTO 1User (accountID, isEmployer,premiumOpt,charge,isAutoPay,selectedMOP,status,paymentDate,email,phone) 
                VALUES (?,?,?,?,?,?,?,?,?,?)";
                echo 'heh1';
                if($stmt = mysqli_prepare($db, $sql))
                {

                    //Statement is valid
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ssssssssss",
                        $param_accountID, $param_isEmployer, $param_premiumOpt, $param_charge,$param_isAutoPay,
                        $param_selectedMOP, $param_status,$param_paymentDate,$param_email,$param_phone);

                    // Set parameters
                    $param_isEmployer=0; //User signup page, hence false
                    $param_charge=0;
                    $param_premiumOpt = 'basic';
                    $param_isAutoPay =0;
                    $param_selectedMOP =null;
                    $param_status = 'activated'; // by default true
                    $param_paymentDate=$str_date;
                    $param_email=$email;
                    $param_phone=$phone;


                    if(mysqli_stmt_execute($stmt))
                    {

                        // Succesfull signup. Redirect to login page
                        $_SESSION['accountID'] = $accountID;
                        $_SESSION['profileName'] = $realname;
                        header("location: user_dashboard.php");
                    }
                    //Failed insert in users. Delete from Account
                    else{
                        echo $db->error;
                        $sql = "DELETE FROM 1Account WHERE accountID= ?";
                        if($stmt = mysqli_prepare($db, $sql))
                        {
                            mysqli_stmt_bind_param($stmt, "s", $param_accountID);
                            // Set parameters
                            $param_accountID = $accountID;
                            // Attempt to execute the prepared statement
                            if(!(mysqli_stmt_execute($stmt)))
                                echo "Something went wrong. Please try again later.";
                        }
                    }
                }
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
    <h2>User Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <!-- real name-->
        <div class="form-group <?php echo (!empty($realname_err)) ? 'has-error' : ''; ?>">
            <label>First and Last name</label>
            <input type="text" name="realname" class="form-control" value="<?php echo $realname; ?>">
            <span class="help-block"><?php echo $realname_err; ?></span>
        </div>
        <!-- Phone-->
        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
            <span class="help-block"><?php echo $phone_err; ?></span>
        </div>
        <!-- email-->
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <!-- username-->
        <div class="form-group <?php echo (!empty($accountID_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="accountID" class="form-control" value="<?php echo $accountID; ?>">
            <span class="help-block"><?php echo $accountID_err; ?></span>
        </div>
        <!--password -->
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
        <br>
        <!--method of payment -->
<!--        <div class="form-group --><?php //echo (!empty($MOP_err)) ? 'has-error' : ''; ?><!--">-->
<!--            <label>Method of payment   </label>-->
<!--            <select name="MOP" size="1">-->
<!--                <option value="" selected disabled hidden>Choose Method of Payment</option>-->
<!--                <option value="credit">Credit Card  </option>-->
<!--                <option value="checking">Checking Account</option>-->
<!--            </select>-->
<!--            <span class="help-block">--><?php //echo $MOP_err; ?><!--</span>-->
<!--        </div>-->
        <!--MOP number -->
<!--        <div class="form-group --><?php //echo (!empty($payment_info_err)) ? 'has-error' : ''; ?><!--">-->
<!--            <label>Credit Card/Checking Account number:   </label>-->
<!--            <input type="text" name="payment_info" class="form-control" value="--><?php //echo $payment_info=''; ?><!--">-->
<!--            <span class="help-block">--><?php //echo $payment_info_err; ?><!--</span>-->
<!--        </div>-->
        <!-- Automatic payment checkbox-->
<!--        <div class="form-group --><?php //echo (!empty($isAutoPay_err)) ? 'has-error' : ''; ?><!--">-->
<!--            <label for="isAutoPay">Activate automatic payment?   </label>-->
<!--            <input type="checkbox" id="isAutoPay" name="isAutoPay"  value="--><?php //echo $payment_info=''; ?><!--" checked>-->
<!--            <span class="help-block">--><?php //echo $isAutoPay_err; ?><!--</span>-->
<!--        </div>-->
        <!-- Account category-->
<!--        <div class="form-group --><?php //echo (!empty($account_type_err)) ? 'has-error' : ''; ?><!--">-->
<!--            <label>Account Type:   </label>-->
<!--            <select name="account_type" size="1">-->
<!--                <option value="" selected disabled hidden>Choose Account Type</option>-->
<!--                <option value="basic">Basic (Free!) </option>-->
<!--                <option value="prime">Prime (10$/Month)</option>-->
<!--                <option value="gold">Gold (20$/Month)</option>-->
<!--            </select>-->
<!--            <span class="help-block">--><?php //echo $account_type_err; ?><!--</span>-->
<!--        </div>-->
        <br>

        <!-- Submit and Reset form-->
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</body>
</html>
