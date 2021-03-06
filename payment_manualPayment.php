<?php
require 'config.php'; //TODO UNCOMMENT
session_start();
$lastPage = "method_of_payment.php";
$_SESSION["lastPage"] = "payment_manualPayment.php";
$creditCardNumber = $holderName1 = $holderName2 = $expDate = $checkingAccountNum = "";
$methodType_err = $creditCardNumber_err = $holderName_err1 = $holderName_err2 = $expDate_err = $checkingAccountNum_err = $login_error = "";
$topUpAmount_err='';

$accountID = $_SESSION['accountID'];

if(isset($_POST["selectedMOP"])){
    $radioVal = $_POST["selectedMOP"];
    $_SESSION['selectedMOP'] = $radioVal;
}elseif(isset($_SESSION['selectedMOP']) and $_SESSION['selectedMOP']!=''){
    $radioVal = $_SESSION['selectedMOP'];
}else{
    header('location: payment_manualPayment_selectMethod.php');
}
$sql = "SELECT methodType, cardNum, holdersName, expirationDate
        FROM `1MethodOfPayment`
        WHERE accountID = '".$accountID."' AND mopDis=".$radioVal.";";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
$methodType=$row['methodType'];
$cardNum = $row['cardNum'];
$holdersName=$row['holdersName'];
$expirationDate=$row['expirationDate'];

if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['Confirm'])) {

    $topUpAmount = mysqli_real_escape_string($db, $_POST['topUpAmount']);

    if($topUpAmount == 0){
        $topUpAmount_err = "Nothing has been topped up.";
    }else{
        //get the current balance
        $sql = "SELECT balance,status
        FROM 1User
        WHERE accountID = '".$_SESSION['accountID']."';";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result);
        $balance=$row['balance'];
        $oldStatus=$row['status'];

        //compute the new balance
        $balance = $balance + $topUpAmount;

        //update DB
        $sql = "UPDATE 1User
                    SET balance=?, status=?
                    WHERE accountID=?";

        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_balance, $param_status, $param_accountID);
            // Set parameters
            $param_accountID = $accountID;
            $param_balance = $balance;
            if($balance<0){
                $param_status='frozen';
            }
            else{
                $param_status='activated';
            }

            mysqli_stmt_execute($stmt);
            echo $stmt->error;
            mysqli_stmt_close($stmt);
        }

        //if an account has been unfreeze
        if($oldStatus=='frozen' and $balance>=0){
            //update DB
            $sql = "DELETE FROM 1FrozenSince
                    WHERE accountID=?";

            if ($stmt = mysqli_prepare($db, $sql)) {
                mysqli_stmt_bind_param($stmt, "s",  $param_accountID);
                // Set parameters
                $param_accountID = $accountID;

                mysqli_stmt_execute($stmt);
                echo $stmt->error;
                mysqli_stmt_close($stmt);
            }
        }


        $msg = 'You have successfully topped up $'.$topUpAmount.'.';

        if($oldStatus=='frozen' and $balance>=0){
            $msg = $msg.'\nYour account has been unfreeze.';
        }


        // email to inform the payment
        if($isENCS){
            $to = $email ;
            $subject = "Money~~";
            $txt = "<html><body><H2> We got your MONEY!<H2>
                <P><H3>Thank you for your $".$topUpAmount." from your bank, you rich dumm dumm.</H3></P>
                <p><H3>Your balance is now $0.</H3></p></body></html>";
            $headers = "From: TheNewIndeed@company.com" . "\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $subject, $txt, $headers);
        }


        echo '<script type="text/javascript">';
        echo "alert('".$msg."');";
        echo 'window.location.href = "method_of_payment.php";';
        echo '</script>';
//        header('method_of_payment.php');
//        echo "heh1";

    }




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
    <title>Top Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>

</head>
<body>
<div class="wrapper">
    <h2>Top Up</h2>
    <div class="form-group <?php echo (!empty($methodType_err)) ? 'has-error' : ''; ?>">
        <label>Method Type:</label>
        <?php
        if($methodType=='credit'){
            echo '  Credit Card';
        }
        else{
            echo '  Authorized checking account';
        }
        ?>
        <span class="help-block"><?php echo $methodType_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($methodType_err)) ? 'has-error' : ''; ?>">
        <?php
        if($methodType=='credit'){
            echo '<label>Card Number:</label>';
        }
        else{
            echo '<label>Account Number:</label>';
        }
        $myCardNum = "";
        for ($i = 1; $i <= strlen($cardNum)-4; $i++) {
            $myCardNum = $myCardNum."*";
        }
        $myCardNum = $myCardNum.substr($cardNum, -4);
        echo '  '.$myCardNum;
        ?>
        <span class="help-block"><?php echo $methodType_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($methodType_err)) ? 'has-error' : ''; ?>">
        <label>Holder's Name:</label>
        <?php echo $holdersName?>

        <span class="help-block"><?php echo $methodType_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($methodType_err)) ? 'has-error' : ''; ?>" style="display: <?php
    if($methodType=='credit'){
        echo 'block';
    }
    else{
        echo 'none';
    }?>">
        <label>Expiration Date:</label>
        <?php echo $expirationDate?>

        <span class="help-block"><?php echo $methodType_err; ?></span>
    </div>



    <form name='submitform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div id="topUpAmount">
            <div class="form-group <?php echo (!empty($topUpAmount_err)) ? 'has-error' : ''; ?>">
                <label id="topUpAmount">Amount to Top Up</label>
                <input type="number" min="0" max="1000000" name="topUpAmount" class="form-control" value="">
                <span class="help-block"><?php echo $topUpAmount_err; ?></span>
            </div>
        </div>

        <span class="help-block"><?php echo $login_error; ?></span>
        <br>
        <div class="form-group">
            <input type="submit" name="Confirm" class="btn btn-primary" value="Confirm">
        </div>
    </form>
    <button onclick="window.location.href='payment_manualPayment_selectMethod.php'">Cancel</button>
</div>
</body>
</html>





