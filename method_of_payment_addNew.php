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

    $sql = "SELECT mopDis
                FROM `1methodofpayment`
                WHERE accountID = 'axel'
                ORDER BY mopDis DESC LIMIT 1;
                ";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $myMOPdis = $row['mopDis'] + 1;

    if($_POST["paymentMethod"] == "credit"){
        $cardNum = mysqli_real_escape_string($db,$_POST['creditCardNumber']);
        $holderName = mysqli_real_escape_string($db,$_POST['holderName1']);
        $expDate = mysqli_real_escape_string($db,$_POST['expDate']."-01");
        $methodType = 'credit';

        // Prepare an insert statement in MOP table
        $sql = "INSERT INTO 1methodofpayment (accountID, mopDis, methodType, cardNum, holdersName, expirationDate)
                VALUES (?,?,?,?,?,?)";

        if($stmt = mysqli_prepare($db, $sql))
        {

            mysqli_stmt_bind_param($stmt, "ssssss", $param_accountNum,$param_myMOPdis,$param_methodType,$param_cardNum,$param_holderName,$param_expDate);
            // Set parameters
            $param_accountNum = $_SESSION['accountID'];
            $param_myMOPdis = $myMOPdis;
            $param_methodType = $methodType;
            $param_cardNum = $cardNum;
            $param_holderName = $holderName;
            $param_expDate = $expDate;

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        else{
            echo $db->error;
        }

    }else{
        $cardNum = mysqli_real_escape_string($db,$_POST['accountNum']);
        $holderName = mysqli_real_escape_string($db,$_POST['holderName2']);
        $methodType = 'checking';

        // Prepare an insert statement in MOP table
        $sql = "INSERT INTO 1methodofpayment (accountID, mopDis, methodType, cardNum, holdersName)
                VALUES (?,?,?,?,?)";

        if($stmt = mysqli_prepare($db, $sql))
        {

            mysqli_stmt_bind_param($stmt, "sssss", $param_accountNum,$param_myMOPdis,$param_methodType,$param_cardNum,$param_holderName);
            // Set parameters
            $param_accountNum = $_SESSION['accountID'];
            $param_myMOPdis = $myMOPdis;
            $param_methodType = $methodType;
            $param_cardNum = $cardNum;
            $param_holderName = $holderName;

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("location: ".$_SESSION["lastPage"]);
        }
        else{
            echo $db->error;
        }

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
                <input type="text" name="holderName1" class="form-control" value="<?php echo $holderName; ?>">
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
                <input type="number" name="accountNum" class="form-control" value="<?php echo $accountNum; ?>">
                <span class="help-block"><?php echo $accountNum_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($holderName_err)) ? 'has-error' : ''; ?>">
                <label>Card Holder's Name</label>
                <input type="text" name="holderName2" class="form-control" value="<?php echo $holderName; ?>">
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
<script src="method_of_payment_addNew_func.js"></script>




