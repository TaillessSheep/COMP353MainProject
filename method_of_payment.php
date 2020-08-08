<?php
require 'config.php';
session_start();


$sql = "SELECT selectedMOP,isAutoPay,balance

        FROM `1User`
        WHERE accountID = '".$_SESSION['accountID']."';";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);
$selectedMOP = $row['selectedMOP'];

$balance = $row['balance'];

$isAutoPay = $row['isAutoPay'];


$unappliedJob=$accountID=$unappliedJob_err=$unapply_result="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST" )
{
    if(isset($_POST['changeDefault'])){
        $radioVal = $_POST["defaultMOP"];

        if($radioVal != $selectedMOP){
            $sql = "UPDATE 1User  SET selectedMOP = ".$radioVal." WHERE accountID = '".$_SESSION['accountID']."';";
            $result = mysqli_query($db,$sql);
            echo '<script>alert("You default payment method has been changed!")</script>';
            $selectedMOP = $radioVal;
        }
    }elseif (isset($_POST['delete'])){
        $accountID = $_SESSION['accountID'];
        $mopDis = $_POST['delete'];
        $sql = "DELETE FROM `1MethodOfPayment` WHERE accountID = '".$accountID."' AND mopDis = ".$mopDis.";";
        $result = mysqli_query($db,$sql);
    }elseif(isset($_POST['autoManualSwitch'])){
        $autoORmanual = $_POST['autoORmanual'];
        if($autoORmanual=='Auto Pay'){
            $isAutoPay = 1;
        }else{
            $isAutoPay = 0;
        }
        $sql = "UPDATE 1User  SET isAutoPay = ".$isAutoPay." WHERE accountID = '".$_SESSION['accountID']."';";
        $result = mysqli_query($db,$sql);
//        echo $db->error;

        if($isAutoPay){
            echo '<script>alert("You have been switched to auto-pay on the first day of every month.")</script>';
        }else{
            echo '<script>alert("You have been switched to manual payment.")</script>';
        }

    }
}
?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<!--    <script src="functions.js"></script>-->
</HEAD>


<BODY>
<?php
$accountID = $_SESSION['accountID'];
$sql = "SELECT accountID FROM 1Admin WHERE accountID= '$accountID'";
$result = mysqli_query($db,$sql);
if(mysqli_num_rows($result) ==1)
{
    require 'admin_dashboard_navbar.php' ;
}
else{
    $sql = "SELECT isEmployer FROM 1User WHERE accountID= '$accountID'";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_array($result);
    if($row['isEmployer']==1)
    {
        require 'employer_dashboard_navbar.php' ;
    }
    else{
        require 'user_dashboard_navbar.php' ;
    }
}

?>
<div align="center"><p><H1>My balance: </H1></p></div>
<div align="center"><p><H1>$<?php echo $balance;?></H1></p></div>
<div align="center"><a href="payment_manualPayment_selectMethod.php?">Top up</a></div>

<H1>My method of payments</H1>

<div align="right" style="padding: 5mm">
    <form name='submitform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <select name="autoORmanual" id="autoORmanual" size="1" style="min-width: 100px">
            <?php
            if($isAutoPay){
                echo "<option value='Auto Pay'SELECTED>Auto Pay</option>";
                echo "<option value='Manual'>Manual Pay</option>";
            }
            else{
                echo "<option value='Auto Pay'>Auto Pay</option>";
                echo "<option value='Manual'SELECTED>Manual Pay</option>";
            }
            ?>
        </select>
        <input type="submit" name="autoManualSwitch" class="btn btn-primary" value="Switch">
    </form>
</div>

<table style="width: 90%; margin: auto">
    <tr>
        <td style="text-align: center;" >
            <form name='submitform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table class="blueTable" style="margin-left: 3%"">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Payment Type</th>
                        <th>Card/Account Number</th>
                        <th>Holder's Name</th>
                        <th>Expiration Data</th>
                        <th>Default Method</th>
                        <th>Delete</th>
                        <th>Edit</th>

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
                            echo "<td><input type=\"radio\" name=\"defaultMOP\" value=\"".$row['mopDis']."\" checked></td>";
                        }else{
                            echo "<td><input type=\"radio\" name=\"defaultMOP\" value=\"".$row['mopDis']."\"></td>";
                        }

                        echo "<td><button class='deleteMOPButton' name='delete' value='".$row['mopDis']."'>Delete</button></td>";

                        echo "<td><a href=\"method_of_payment_edit.php?mopDis=".$row['mopDis']."\">Edit</a></td>";

                        echo "</tr>";
                        $counter ++;
                    }
                    ?>



                    </tbody>
                </table>
                <input type="submit" name="changeDefault" class="btn btn-primary" value="Change Default">
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
