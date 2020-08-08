<?php
require 'config.php'; //TODO UNCOMMENT
session_start();
$lastPage = $_SESSION["lastPage"];
$_SESSION["lastPage"] = "";
$creditCardNumber = $holderName1 = $holderName2 = $expDate = $checkingAccountNum = "";
$methodType_err = $creditCardNumber_err = $holderName_err1 = $holderName_err2 = $expDate_err = $checkingAccountNum_err = $login_error = "";

$radioVal = $_POST["selectedMOP"];

$sql = "SELECT methodType, cardNum, holdersName, expirationDate
        FROM `1MethodOfPayment`
        WHERE accountID = '".$_SESSION['accountID']."' AND mopDis=".$radioVal.";";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
$methodType=$row['methodType'];
$cardNum = $row['cardNum'];
$holdersName=$row['holdersName'];
$expirationDate=$row['expirationDate'];

echo $methodType.$cardNum.$holdersName.$expirationDate;



if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['Confirm'])) {
//    $login_error="";
//    $accountID_err="";
//    $password_err="";
//
//    $sql = "SELECT mopDis
//            FROM `1MethodOfPayment`
//            WHERE accountID = ".$_SESSION['accountID']."
//            ORDER BY mopDis DESC LIMIT 1;
//            ";
//    $result = mysqli_query($db,$sql);
//    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
//    $myMOPdis = $row['mopDis'] + 1;
//
//    if($_POST["paymentMethod"] == "credit") {
//        if (empty(trim($_POST['creditCardNumber']))) {
//            $creditCardNumber_err = "Please enter a credit card number.";
//        } else {
//            $cardNum = mysqli_real_escape_string($db, $_POST['creditCardNumber']);
//        }
//
//        if (empty(trim($_POST['holderName1']))) {
//            $holderName_err1 = "Please enter the name of the card's holder.";
//        } else {
//            $holderName1 = mysqli_real_escape_string($db, $_POST['holderName1']);
//        }
//
//        if (empty(trim($_POST['expDate']))) {
//            $expDate_err = "Please select the expiration date.";
//        } else {
//            $expDate = mysqli_real_escape_string($db, $_POST['expDate'] . "-01");
//        }
//
//        $methodType = 'credit';
//
//        if (empty($creditCardNumber_err) && empty($holderName_err1) && empty($expDate_err) ){
//            // Prepare an insert statement in MOP table
//            $sql = "INSERT INTO 1MethodOfPayment (accountID, mopDis, methodType, cardNum, holdersName, expirationDate)
//                    VALUES (?,?,?,?,?,?)";
//            if ($stmt = mysqli_prepare($db, $sql)) {
//                mysqli_stmt_bind_param($stmt, "ssssss", $param_accountNum, $param_myMOPdis, $param_methodType, $param_cardNum, $param_holderName, $param_expDate);
//                // Set parameters
//                $param_accountNum = $_SESSION['accountID'];
//                $param_myMOPdis = $myMOPdis;
//                $param_methodType = $methodType;
//                $param_cardNum = $cardNum;
//                $param_holderName = $holderName1;
//                $param_expDate = $expDate;
//
//                mysqli_stmt_execute($stmt);
//                echo $stmt->error;
//                mysqli_stmt_close($stmt);
//                header("location: " . $lastPage);
//            }
////            else {
////                echo $db->error;
////            }
//        }
//
//    }else{
//        if(empty(trim($_POST['checkingAccountNum']))){
//            $checkingAccountNum_err = "Please enter your checking account number.";
//        }else{
//            $checkingAccountNum = mysqli_real_escape_string($db,$_POST['checkingAccountNum']);
//        }
//
//        if(empty(trim($_POST['holderName2']))){
//            $holderName_err2 = "Please enter the name of the card's holder.";
//        }else{
//            $holderName2 = mysqli_real_escape_string($db,$_POST['holderName2']);
//        }
//
//        $methodType = 'checking';
//
//        if(empty($checkingAccountNum_err) && empty($holderName_err2)) {
//            // Prepare an insert statement in MOP table
//            $sql = "INSERT INTO 1MethodOfPayment (accountID, mopDis, methodType, cardNum, holdersName)
//                    VALUES (?,?,?,?,?)";
//
//            if ($stmt = mysqli_prepare($db, $sql)) {
//
//                mysqli_stmt_bind_param($stmt, "sssss", $param_accountNum, $param_myMOPdis, $param_methodType, $param_cardNum, $param_holderName);
//                // Set parameters
//                $param_accountNum = $_SESSION['accountID'];
//                $param_myMOPdis = $myMOPdis;
//                $param_methodType = $methodType;
//                $param_cardNum = $checkingAccountNum;
//                $param_holderName = $holderName2;
//
//                mysqli_stmt_execute($stmt);
//                mysqli_stmt_close($stmt);
//                header("location: " . $lastPage);
//            } else {
////                echo $db->error;
//            }
//        }
//
//    }

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
        for ($i = 1; $i <= strlen($row['cardNum'])-4; $i++) {
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
            <div class="form-group <?php echo (!empty($checkingAccountNum_err)) ? 'has-error' : ''; ?>">
                <label id="topUpAmount">Amount to Top UP</label>
                <input type="number" min="0" name="topUpAmount" class="form-control" value="">
                <span class="help-block"><?php echo $checkingAccountNum_err; ?></span>
            </div>
        </div>

        <span class="help-block"><?php echo $login_error; ?></span>
        <br>
        <div class="form-group">
            <input type="submit" name="Confirm" class="btn btn-primary" value="Confirm">
        </div>
    </form>
    <button onclick="window.location.href='<?php echo $lastPage; ?>'">Cancel</button>
</div>
</body>
</html>





