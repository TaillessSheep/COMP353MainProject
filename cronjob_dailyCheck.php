<?php
require 'config.php';
require 'isENCS.php';
session_start();


$sql = "SELECT *
        FROM 1FrozenSince";
$result1 = mysqli_query($db, $sql);
$count = mysqli_num_rows($result1);
echo '<p>'.$count.' accounts in total</p>';
//$counter = 1;
while ($row = mysqli_fetch_array($result1)) {
    $txt = '';
    $accountID  = $row['accountID'];
    $fDate      = date_create($row['date']);
    echo '<p>'.$accountID.' | frozen since:'.date_format($fDate,'y-m-d');

    //get the next payment date
    $today = new DateTime('now');
    $daysPassed = intval(date_diff($today,$fDate)->format('%a'));
    echo " | for: ".$daysPassed."days";

    // if over a year
    if($daysPassed>365){
        echo " | deactivated";
        // update the DB
        $sql = "UPDATE 1User
                    SET status = 'deactivated'
                    WHERE accountID=?";
        if ($stmt = mysqli_prepare($db, $sql)) {
            mysqli_stmt_bind_param($stmt, "s",  $param_accountID);
            // Set parameters
            $param_accountID    = $accountID;

            mysqli_stmt_execute($stmt);
            echo $stmt->error;
            mysqli_stmt_close($stmt);


            $sql = "DELETE FROM 1FrozenSince
                    WHERE accountID=?";
            if ($stmt = mysqli_prepare($db, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_accountID);
                // Set parameters
                $param_accountID    = $accountID;

                mysqli_stmt_execute($stmt);
                echo $stmt->error;
                mysqli_stmt_close($stmt);

                $sql = "SELECT email FROM 1User WHERE accountID='".$accountID."';";
                $result = mysqli_query($db, $sql);
                $row = mysqli_fetch_array($result);
                $email = $row['email'];

                $txt = "<html><body><H2> Account deactivated!<H2>
                        <P><H3>Due to a long period (over a year) of suffering balance in your account,</H3></P>
                        <P><H3>your account has been deactivated.</H3></P>                        
                        <P><H3>If you would like to reactivated you account, please contact our customer service.</H3></P>
                        <p><H3>Thank you for choosing Th New Indeed(the better Indeed :P).</H3></p></body></html>";

            }

        }
    }
    // if no over a year but on a notifying day
    elseif ($daysPassed%7==0){
        echo " | frozen notice";

        $sql = "SELECT email FROM 1User WHERE accountID='".$accountID."';";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        $email = $row['email'];

//        echo $db->error;


        $txt = "<html><body><H2> Account frozen!<H2>
                <P><H3>Due to a negative balance in your account, you account is now frozen.</H3></P>
                <P><H3>A frozen account can not access to any feature of the website except the payment section.</H3></P>                        
                <P><H3>If you would like to have more information, please contact our customer service.</H3></P>
                <p><H3>Thank you for choosing Th New Indeed(the better Indeed :P).</H3></p></body></html>";
    }

    if($txt !='' and $isENCS){
        $to = $email ;
        $subject = "Account Status";
        $headers = "From: TheNewIndeed@company.com" . "\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $txt, $headers);
    }


}
