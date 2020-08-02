<?php
require 'config.php';
session_start();


$unappliedJob=$accountID=$unappliedJob_err=$unapply_result="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
//    echo "heh";
    $radioVal = $_POST["defaultMOP"];
    echo $radioVal.$_SESSION['accountID'];
    $sql = "UPDATE 1user  SET selectedMOP = ".$radioVal." WHERE accountID = '".$_SESSION['accountID']."';";
    $result = mysqli_query($db,$sql);

//    // Validate jobID
//    if (empty(trim($_POST["appliedJob"])))
//    {
//        $unappliedJob_err = "There was an error removing application to the job";
//    } else
//    {
//        $unappliedJob = trim($_POST["appliedJob"]);
//    }
//    // Validate ID
//    if (empty(trim($_SESSION["accountID"])))
//    {
//        $unappliedJob_err = "There was an error removing application to the job";
//    } else
//    {
//        $accountID = trim($_SESSION["accountID"]);
//    }
//    if (empty($unappliedJob_err))
//    {
//        $sql = "DELETE FROM `1Applied` WHERE jobID= ? AND jobSeekerID = ?";
//        if ($stmt = mysqli_prepare($db, $sql))
//        {
//            // Bind variables to the prepared statement as parameters
//            mysqli_stmt_bind_param($stmt, "ss",  $param_jobID,$param_accountID);
//
//            // Set parameters
//            $param_jobID = $unappliedJob;
//            $param_accountID = $accountID;
//
//            if (mysqli_stmt_execute($stmt))
//            {
//                $apply_result = 'Your have sucessfully applied to job #.'.$unappliedJob;
//            } else
//            {
//                $unappliedJob_err = "Something went wrong. You may have already applied to this job.";
//            }
//        }
//    }

}





?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="functions.js">
    </script>
</HEAD>

<BODY>
<?php require 'user_dashboard_navbar.php' //nav bar
?>

<H1>My method of payments</H1>
<table style="width: 90%;">
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

            </tr>
            </thead>
            <tfoot>

            </tfoot>
            <tbody id="tableBody">

            <?php
            $sql = "SELECT selectedMOP
                    FROM `1user`
                    WHERE accountID = '".$_SESSION['accountID']."';";
            $result = mysqli_query($db,$sql);
            $row = mysqli_fetch_array($result);
            $selectedMOP = $row['selectedMOP'];

            $sql = "SELECT mopDis,methodType, cardNum, holdersName, expirationDate
                    FROM `1methodofpayment`
                    WHERE accountID = '".$_SESSION['accountID']."';";
            $result = mysqli_query($db,$sql);
            $counter = 1;
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <script> var jobdata = <?php echo json_encode($row);?></script>
                <?php
                echo "<tr>";
                echo "<td>".$counter."</td>";
                echo "<td>".$row['methodType']."</td>";
                echo "<td>".$row['cardNum']."</td>";
                echo "<td>".$row['holdersName']."</td>";
                echo "<td>".$row['expirationDate']."</td>";

                if($selectedMOP == $row['mopDis']){
                    echo "<td><input type=\"radio\" name=\"defaultMOP\" value=\"".$row['mopDis']."\" checked></td>";
                }else{
                    echo "<td><input type=\"radio\" name=\"defaultMOP\" value=\"".$row['mopDis']."\"></td>";
                }

                echo "</tr>";
                $counter ++;
            }
            ?>



            </tbody>
            </table>
            <input type="submit" class="btn btn-primary" value="Change Default">
            </form>

</td>


<br>




</BODY>
</HTML>
