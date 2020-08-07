<?php
require 'config.php';
session_start();



$sql = "SELECT accountID,charge,selectedMOP,email,balance,isAutoPay,status
        FROM 1User
        WHERE status != 'deactivated';";
$result1 = mysqli_query($db, $sql);
$count = mysqli_num_rows($result1);
echo '<p>'.$count.' accounts in total</p>';
//$counter = 1;
while ($row = mysqli_fetch_array($result1)) {
    $accountID      = $row['accountID'];
    $charge         = $row['charge'];
    $selectedMOP    = $row['selectedMOP'];
    $email          = $row['email'];
    $balance        = $row['balance'];
    $isAutoPay      = $row['isAutoPay'];
    $status         = $row['status'];
    echo '<p>'.$accountID.' charged:$'.$charge;

    //get the next payment date
    $date = new DateTime('now');
    $date->modify('first day of next month');
    $str_date=date_format($date, 'Y-m-d');

    // if the account balance is sufficient
    if($balance >= $charge){
        $balance = $balance - $charge;
        $sql = "UPDATE 1User
                    SET balance=?, paymentDate = ?
                    WHERE accountID=?";

        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_balance, $param_paymentDate, $param_accountID);
            // Set parameters
            $param_balance = $balance;
            $param_paymentDate = $str_date;
            $param_accountID = $accountID;

            mysqli_stmt_execute($stmt);
            echo $stmt->error;
            mysqli_stmt_close($stmt);
        }
        // email the user about the auto-withdrawal
        $txt = "<html><body>
                <P><H3>$".$cost." has been deducted from your account, you rich dumm dumm.</H3></P>
                <p><H3>You balance is now $".$balance.".</H3></p></body></html>";
    }
    // if account balance is not sufficient
    // but auto-payment is enabled
    elseif ($isAutoPay){
        $deducted = $balance;
        $charge = $charge - $balance;
        $balance = 0;

        // get the selected payment method
        $sql = "SELECT methodType,cardNum,holdersName,expirationDate
                FROM 1MethodOfPayment
                WHERE accountID = '".$accountID."' AND mopDis = ".$selectedMOP." ;";
        $result = mysqli_query($db, $sql);
        echo $db->error;
        $row = mysqli_fetch_array($result);

        // script to withdrawal $charge amount
        // well...

        // update the DB
        $sql = "UPDATE 1User
                    SET balance=0, paymentDate = ?
                    WHERE accountID=?";
        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_paymentDate, $param_accountID);
            // Set parameters
            $param_paymentDate = $str_date;
            $param_accountID = $accountID;

            mysqli_stmt_execute($stmt);
            echo $stmt->error;
            mysqli_stmt_close($stmt);
        }

        // email the user about the auto-withdrawal
        $txt = "<html><body><H2> We got your MONEY!<H2>
                <P><H3>$".$cost." has been deducted from your account.</H3></P>
                <P><H3>And thank you for your $".$cost." from your bank, you rich dumm dumm.</H3></P>
                <p><H3>Your balance is now $0.</H3></p></body></html>";

    }
    // if not able to pay
    else{
        $balance = $balance - $charge;
        $status = 'frozen';

        // update the DB
        $sql = "UPDATE 1User
                    SET balance=?, status = ?
                    WHERE accountID=?";
        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_balance, $param_status, $param_accountID);
            // Set parameters
            $param_balance      = $balance;
            $param_status       = $status;
            $param_accountID    = $accountID;

            mysqli_stmt_execute($stmt);
            echo $stmt->error;
            mysqli_stmt_close($stmt);
        }

        // email the user about the auto-withdrawal
        $txt = "<html><body><H1>Yo! Give us your MONEY!<H1>
                <P><H3>You do not have sufficient balance!</H3></P>
                <p><H3>Your balance is now $".$balance.".</H3></p>
                <p><H1>Your account is now FROZEN!</H1></p>
                </body></html>";
    }

    // email the user about the auto-withdrawal
    $to = $email ;
    $subject = "Money";
    $cost = $charge;
//    $txt = "<html><body><H2> We got your MONEY!<H2><P><H3>Thank you for your $".$cost.", you rich dumm dumm.</H3></P></body></html>";
    $headers = "From: TheNewIndeed@company.com" . "\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    mail($to, $subject, $txt, $headers);

    echo " new balance:".$balance." new status:".$status." done \n";
}
