<?php
require 'config.php';
session_start();
$_SESSION["lastPage"] = "payment_manualPayment_selectMethod.php";

$sql = "SELECT selectedMOP,balance
        FROM `1User`
        WHERE accountID = '".$_SESSION['accountID']."';";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
$selectedMOP = $row['selectedMOP'];
$balance = $row['balance'];

// Processing form data when form is submitted
//if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST" )
//{
//    if(isset($_POST['changeDefault'])){
//        $radioVal = $_POST["defaultMOP"];
//
////        if($radioVal != $selectedMOP){
////            $sql = "UPDATE 1User  SET selectedMOP = ".$radioVal." WHERE accountID = '".$_SESSION['accountID']."';";
////            $result = mysqli_query($db,$sql);
////            echo '<script>alert("You default payment method has been changed!")</script>';
////            $selectedMOP = $radioVal;
////        }
//    }elseif (isset($_POST['delete'])){
//        $accountID = $_SESSION['accountID'];
//        $mopDis = $_POST['delete'];
//        $sql = "DELETE FROM `1MethodOfPayment` WHERE accountID = '".$accountID."' AND mopDis = ".$mopDis.";";
//        $result = mysqli_query($db,$sql);
//    }
//}
?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <!--    <script src="functions.js"></script>-->
</HEAD>


<?php require 'user_dashboard_navbar.php' //nav bar?>

<H1>Top Up</H1>
<div align="center"><p><H2>My balance: </H2></p></div>
<div align="center"><p><H2>$<?php echo $balance;?></H2></p></div>


<H3>Select a payment method</H3>
<table style="width: 90%; margin: auto">
    <tr>
        <td style="text-align: center;" >
            <form name='submitform' action="payment_manualPayment.php" method="post">
                <table class="blueTable" style="margin-left: 3%"">
                <thead>
                <tr>
                    <th></th>
                    <th>Payment Type</th>
                    <th>Card/Account Number</th>
                    <th>Holder's Name</th>
                    <th>Expiration Data</th>
                    <th>Use this to pay</th>

                </tr>
                </thead>

                <tbody id="tableBody">

                <?php
                $sql = "SELECT mopDis,methodType, cardNum, holdersName, expirationDate
                        FROM `1MethodOfPayment`
                        WHERE accountID = '".$_SESSION['accountID']."';";
                $result = mysqli_query($db,$sql);
                $counter = 1;
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$counter."</td>";
                    echo "<td>".$row['methodType']."</td>";
                    $myCardNum = "";
                    for ($i = 1; $i <= strlen($row['cardNum'])-4; $i++) {
                        $myCardNum = $myCardNum."*";
                    }
                    $myCardNum = $myCardNum.substr($row['cardNum'], -4);
                    echo "<td>".$myCardNum."</td>";
                    echo "<td>".$row['holdersName']."</td>";
                    $expDate = substr($row['expirationDate'], 0,7);
                    echo "<td>".$expDate."</td>";

                    if($selectedMOP == $row['mopDis']){
                        echo "<td><input type=\"radio\" name=\"selectedMOP\" value=\"".$row['mopDis']."\" checked></td>";
                    }else{
                        echo "<td><input type=\"radio\" name=\"selectedMOP\" value=\"".$row['mopDis']."\"></td>";
                    }

                    echo "</tr>";
                    $counter ++;
                }
                ?>



                </tbody>
</table>
<input type="submit" name="select" class="btn btn-primary" value="Select">
</form>

</td>
</tr>

</table>
<br>


<div style="margin: auto; text-align: center";>
    <?php
    $_SESSION["lastPage"] = "method_of_payment.php";
    ?>
    <a href="method_of_payment_addNew.php">Add a new method of payment</a>
</div>



</BODY>
</HTML>
    <!--<script src="method_of_payment.js"></script>-->

