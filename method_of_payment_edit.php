<?php
require 'config.php'; //TODO UNCOMMENT
session_start();
$lastPage = $_SESSION["lastPage"];
$accountID = $_SESSION["accountID"];
//check if mopDis comes from url or session
if(isset($_GET["mopDis"])){
    $mopDis = $_GET["mopDis"];
    $_SESSION["mopDis"] = $mopDis;
}else{
    $mopDis = $_SESSION["mopDis"];
}

$creditCardNumber = $holderName1 = $holderName2 = $expDate = $checkingAccountNum = "";
$methodType_err = $creditCardNumber_err = $holderName_err1 = $holderName_err2 = $expDate_err = $checkingAccountNum_err = $login_error = "";

//fetch info from db
$sql = "SELECT methodType,cardNum,holdersName,expirationDate
        FROM 1MethodOfPayment
        WHERE accountID = '".$accountID."'
          AND mopDis = ".$mopDis.";";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
$methodType = $row['methodType'];

// edit a credit card MOP
if($methodType == "credit"){
    $creditCardNumber   = $row['cardNum'];
    $holderName1        = $row['holdersName'];
    $expDate            = $row['expirationDate'];
    $expDate            = substr($expDate, 0,7);// convert into month type
}
// edit a checking account MOP
else{
    $checkingAccountNum = $row['cardNum'];
    $holderName2        = $row['holdersName'];
}


if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['Confirm'])) {
    $accountID_err="";

    // editing a credit card MOP
    if($methodType == "credit") {
        if (empty(trim($_POST['creditCardNumber']))) {
            $creditCardNumber_err = "Please enter a credit card number.";
        } else {
            $cardNum = mysqli_real_escape_string($db, $_POST['creditCardNumber']);
        }

        if (empty(trim($_POST['holderName1']))) {
            $holderName_err1 = "Please enter the name of the card's holder.";
        } else {
            $holderName1 = mysqli_real_escape_string($db, $_POST['holderName1']);
        }

        if (empty(trim($_POST['expDate']))) {
            $expDate_err = "Please select the expiration date.";
        } else {
            $expDate = mysqli_real_escape_string($db, $_POST['expDate'] . "-01");
        }

//        $methodType = 'credit';

        if (empty($creditCardNumber_err) && empty($holderName_err1) && empty($expDate_err) ){
            // Prepare an insert statement in MOP table
            $sql = "UPDATE 1MethodOfPayment
                    SET cardNum=?, holdersName = ?, expirationDate = ?
                    WHERE accountID=? AND mopDis=?";

            if ($stmt = mysqli_prepare($db, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssss", $param_cardNum, $param_holderName, $param_expirationDate, $param_accountID, $param_mopDis);
                // Set parameters
                $param_cardNum = $cardNum;
                $param_holderName = $holderName1;
                $param_expirationDate = $expDate;
                $param_accountID = $accountID;
                $param_mopDis = $mopDis;

                mysqli_stmt_execute($stmt);
                echo $stmt->error;
                mysqli_stmt_close($stmt);
                header("location: " . $lastPage);
            }
//            else {
//                echo $db->error;
//            }
        }else{
            $expDate            = substr($expDate, 0,7);
        }

    }
    // editing a checking account MOP
    else{
        if(empty(trim($_POST['checkingAccountNum']))){
            $checkingAccountNum_err = "Please enter your checking account number.";
        }else{
            $checkingAccountNum = mysqli_real_escape_string($db,$_POST['checkingAccountNum']);
        }

        if(empty(trim($_POST['holderName2']))){
            $holderName_err2 = "Please enter the name of the card's holder.";
        }else{
            $holderName2 = mysqli_real_escape_string($db,$_POST['holderName2']);
        }

        $methodType = 'checking';

        if (empty($checkingAccountNum_err) && empty($holderName_err2)){
            // Prepare an insert statement in MOP table
            $sql = "UPDATE 1MethodOfPayment
                    SET cardNum=?, holdersName = ?
                    WHERE accountID=? AND mopDis=?";

            if ($stmt = mysqli_prepare($db, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $param_cardNum, $param_holderName, $param_accountID, $param_mopDis);
                // Set parameters
                $param_cardNum = $checkingAccountNum;
                $param_holderName = $holderName2;
                $param_accountID = $accountID;
                $param_mopDis = $mopDis;

                mysqli_stmt_execute($stmt);
                echo $stmt->error;
                mysqli_stmt_close($stmt);
                header("location: " . $lastPage);
            }
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
    <title>Modify Method of Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>

</head>
<body>
<div class="wrapper">
    <h2>Modify Method of Payment</h2>
    <form name='submitform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<!--    credit card block-->
        <div id="creditInfo" style="display: <?php if($methodType=="credit")echo "block"; else echo "none"?>">
            <div class="form-group <?php echo (!empty($creditCardNumber_err)) ? 'has-error' : ''; ?>">
                <label id="filed1">Credit Card Number</label>
                <input type="number" name="creditCardNumber" class="form-control" value="<?php echo $creditCardNumber; ?>">
                <span class="help-block"><?php echo $creditCardNumber_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($holderName_err1)) ? 'has-error' : ''; ?>">
                <label>Card Holder's Name</label>
                <input type="text" name="holderName1" class="form-control" value="<?php echo $holderName1; ?>">
                <span class="help-block"><?php echo $holderName_err1; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($expDate_err)) ? 'has-error' : ''; ?>">
                <label>Expiration Date</label>
                <input type="month" name="expDate" class="form-control" value="<?php echo $expDate; ?>">
                <span class="help-block"><?php echo $expDate_err; ?></span>
            </div>
        </div>

<!--    bank account block-->
        <div id="bankAccountInfo" style="display: <?php if($methodType!="credit")echo "block"; else echo "none"?>">
            <div class="form-group <?php echo (!empty($checkingAccountNum_err)) ? 'has-error' : ''; ?>">
                <label id="accountNum">Bank Account Number</label>
                <input type="number" name="checkingAccountNum" class="form-control" value="<?php echo $checkingAccountNum; ?>">
                <span class="help-block"><?php echo $checkingAccountNum_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($holderName_err2)) ? 'has-error' : ''; ?>">
                <label>Card Holder's Name</label>
                <input type="text" name="holderName2" class="form-control" value="<?php echo $holderName2; ?>">
                <span class="help-block"><?php echo $holderName_err2; ?></span>
            </div>
        </div>


        <span class="help-block"><?php echo $login_error; ?></span>
        <br>
        <div class="form-group">
            <input type="submit" name="Confirm" class="btn btn-primary" value="Confirm">
            <input type="reset" id="button_MOPreset" class="btn btn-default" value="Reset">
        </div>
    </form>
    <button onclick="window.location.href='<?php echo $lastPage; ?>'">Cancel</button>
</div>
</body>
</html>
<script src="method_of_payment_addNew.js"></script>




