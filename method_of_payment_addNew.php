<?php
require 'config.php'; //TODO UNCOMMENT
session_start();
$lastPage = $_SESSION["lastPage"];
$creditCardNumber = $holderName = $expDate = $accountNum = "";
$methodType_err = $creditCardNumber_err = $holderName_err = $expDate_err = $accountNum_err = $login_error = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_error="";
    $accountID_err="";
    $password_err="";

    echo $_POST["paymentMethod"];
    // ID and password sent from form

//    $accountID = mysqli_real_escape_string($db,$_POST['accountID']);
//    $accountID = $_POST['accountID'];

    if($_POST["paymentMethod"] == "credit"){

    }else{

    }

//    $sql = "SELECT accountID,profileName FROM 1Account WHERE accountID = '$accountID' and password = '$password'";
//    $result = mysqli_query($db,$sql);
//    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
//    $count = mysqli_num_rows($result);
//    $_SESSION['profileName']    = $row['profileName'];
//    // If result matched $accountID and $password, table row must be 1 row
//    if($count == 1) {
//        // Verify if user's account is activated and if it is an employer or JS
//        $sql = "SELECT activation,isEmployer FROM 1User WHERE accountID= '$accountID'";
//        $result = mysqli_query($db,$sql);
//        $row = mysqli_fetch_array($result);
//        if($row['activation']==1){
//            $_SESSION['accountID']  = $accountID;
//            if( $row['isEmployer']==1) //Valid employer account
//            {
//                header("location: employer_dashboard.php");
//            }
//            elseif($row['isEmployer']==0) //Valid JS account
//            {
//                header("location: user_dashboard.php");
//            }
//
//        }else{
//            $error="Your account is deactivated. Contact an administrator.";
//        }
//
//
//        if($row['activation']==1 && $row['isEmployer']==1) //Valid employer account
//        {
//            $_SESSION['accountID']=$accountID;
//            header("location: employer_dashboard.php");
//        }
//        elseif($row['activation']==1 && $row['isEmployer']==0) //Valid JS account
//        {
//            $_SESSION['accountID']=$accountID;
//            header("location: user_dashboard.php");
//        }
//
//
//    }else {
//        $error = "Your Login Name or Password is invalid";
//    }
    header("location: ".$_SESSION["lastPage"]);
}
?>
<?php
//$to = "515094854w@gmail.com";
//$subject = "My subject";
//$txt = "Hello world!";
//$headers = "From: webmaster@example.com" . "\r\n" .
//    "CC: somebodyelse@example.com";
//
//mail($to,$subject,$txt,$headers);
//?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Method of Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>

</head>
<body>
<div class="wrapper">
    <h2>New Payment Method</h2>
    <form name='submitform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($methodType_err)) ? 'has-error' : ''; ?>">
            <label>Method Type</label>
            <select class="paymentMethod" id="paymentMethod" name="paymentMethod">
                <option value="credit">Credit card</option>
                <option value="bankAccount">Bank account with authorization</option>
            </select>
            <span class="help-block"><?php echo $methodType_err; ?></span>
        </div>

<!--    credit card block-->
        <div id="creditInfo" style="display: block">
            <div class="form-group <?php echo (!empty($creditCardNumber_err)) ? 'has-error' : ''; ?>">
                <label id="filed1">Credit Card Number</label>
                <input type="number" name="creditCardNumber" class="form-control" value="<?php echo $creditCardNumber; ?>">
                <span class="help-block"><?php echo $creditCardNumber_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($holderName_err)) ? 'has-error' : ''; ?>">
                <label>Card Holder's Name</label>
                <input type="text" name="holderName" class="form-control" value="<?php echo $holderName; ?>">
                <span class="help-block"><?php echo $holderName_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($expDate_err)) ? 'has-error' : ''; ?>">
                <label>Expiration Date</label>
                <input type="month" name="expDate" class="form-control" value="<?php echo $expDate; ?>">
                <span class="help-block"><?php echo $expDate_err; ?></span>
            </div>
        </div>

<!--    bank account block-->
        <div id="bankAccountInfo" style="display: none">
            <div class="form-group <?php echo (!empty($accountNum_err)) ? 'has-error' : ''; ?>">
                <label id="accountNum">Bank Account Number</label>
                <input type="text" name="accountNum" class="form-control" value="<?php echo $accountNum; ?>">
                <span class="help-block"><?php echo $accountNum_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($holderName_err)) ? 'has-error' : ''; ?>">
                <label>Card Holder's Name</label>
                <input type="text" name="holderName" class="form-control" value="<?php echo $holderName; ?>">
                <span class="help-block"><?php echo $holderName_err; ?></span>
            </div>
        </div>


        <span class="help-block"><?php echo $login_error; ?></span>
        <br>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Confirm">
            <input type="reset" id="button_MOPreset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>
</body>
</html>
<script src="functions.js"></script>




