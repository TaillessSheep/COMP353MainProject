<?php
require 'config.php';
session_start();
// Processing form data when form is submitted
$deactivate_result='';
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST['activateAccount']))
    {
        $accountID = $_POST['switchStatusID'];
        $sql = "UPDATE `1User` SET status = 'activated' WHERE  accountID='".$accountID ."'";
        if(mysqli_query($db,$sql)){
            $deactivate_result='You have sucessfully activated the user.';
        }
    }
    if(isset($_POST['deactivateAccount']))
    {
        $accountID = $_POST['switchStatusID'];
        echo 'Account ID: '.$accountID;
        $sql = "UPDATE `1User` SET status = 'deactivated' WHERE  accountID='".$accountID ."'";
        if(mysqli_query($db,$sql)){
            $deactivate_result='You have sucessfully deactivated the user.';
        }
    }
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
<?php require 'admin_dashboard_navbar.php'; //nav bar
$seeOutstanding=false;
?>
<H1>User Reports</H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;vertical-align: top"" >
            <table class="blueTable" style="margin-left: 3%;"">
            <?php
            //Reset table to all applications
            if(isset($_POST['resetTable']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance FROM `1User` ORDER BY isEmployer DESC";
                $result = mysqli_query($db,$sql);
            }
            // See Employers
            elseif(isset($_POST['seeEmployers']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM `1User`
                        WHERE isEmployer='" . 1 ."'
                        ORDER BY isEmployer DESC";
                $result = mysqli_query($db,$sql);
            }
            //See JS
            elseif(isset($_POST['seeJS']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM `1User`
                        WHERE isEmployer='" . 0 ."'
                        ORDER BY isEmployer DESC";
                $result = mysqli_query($db,$sql);
            }
            // Search by premium option
            elseif(isset($_POST['filterPremium']))
            {
                $category = $_POST['accountCategory'];
                if($category == 'all' || empty($category))
                {
                    $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance FROM `1User`ORDER BY isEmployer DESC";
                }
                else
                {
                    $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM `1User`
                        WHERE premiumOpt='" . $category ."'
                        ORDER BY isEmployer DESC";
                }
                $result = mysqli_query($db,$sql);
            }
            //See outstanding balance accounts
            elseif(isset($_POST['seeOutstanding']))
            {
                $seeOutstanding=true;
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM `1User`
                        WHERE balance<'" . 0 ."'
                        ORDER BY isEmployer DESC";
                $result = mysqli_query($db,$sql);
            }
            //Default table. All users
            else{
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance FROM `1User`ORDER BY isEmployer DESC";
                $result = mysqli_query($db,$sql);
            }
            ?>
            <thead>
            <tr>
                <th>Account ID</th>
                <th>Account Type</th>
                <th>Account Category</th>
                <th>Charge</th>
                <th>Status</th>
                <?php
                if($seeOutstanding){echo"<th>Became Outstanding Date </th>";}?>
                <th>Email</th>
                <th>Balance</th>
                <th>Deactivate</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            <?php
            $result = mysqli_query($db,$sql);
            while ($row = mysqli_fetch_array($result)) {
                if($seeOutstanding)
                {
                    $sql = "SELECT date FROM `1FrozenSince` WHERE accountID = '".$row['accountID']."'";
                    $result2 = mysqli_query($db,$sql);
                    $row2 = mysqli_fetch_array($result2);
                }

                if($row['isEmployer']==1)
                {
                    $accountType = 'Employer';
                }
                elseif($row['isEmployer']==0)
                {
                    $accountType = 'Job Seeker';
                }
                echo "<tr>";
                echo "<td>".$row['accountID']."</td>";
                echo "<td>".$accountType."</td>";
                echo "<td>".$row['premiumOpt']."</td>";
                echo "<td>".$row['charge']."</td>";
                echo "<td>".$row['status']."</td>";
                if($seeOutstanding){echo "<td>".$row2['date']."</td>";}
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['balance']."</td>";
                if($row['status']=='activated' ||$row['status']=='frozen' )
                {
                    echo "<td>
                        <form method='post'>
                        <input type='submit' value='Deactivate' name='deactivateAccount'>    
                        <input type='hidden' value='".$row['accountID']."' name='switchStatusID'>
                        </form>
                         </td>";
                }
                elseif($row['status']=='deactivated')
                {
                    echo "<td>
                        <form method='post'>
                        <input type='submit' value='Activate' name='activateAccount'>    
                        <input type='hidden' value='".$row['accountID']."' name='switchStatusID'>
                        </form>
                          </td>";
                }
                echo "</tr>";
            }
            ?>
            </tbody>
</table>
</td>
<td style="text-align: center">
    <table style="margin-left: 50px; border-spacing: 150px; text-align: left">
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" value="Reset" name="resetTable" style="min-width: 170px">
                </td>
                <td style="padding-bottom: 1em; padding-left: 1em">
                </td>
            </form>
        </tr>
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" value="See Only Employers" name="seeEmployers" style="min-width: 170px">
                </td>
            </form>
        </tr>
        <tr>
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" value="See Only Job Seekers" name="seeJS" style="min-width: 170px">
                </td>
            </form>
        </tr>
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" value="Filter by Premium Accounts" name="filterPremium" style="min-width: 170px">
                </td>
                <td style="padding-bottom: 1em;padding-left: 1em">
                    <select name="accountCategory" size="1" style="min-width: 150px">
                        <option value=''hidden>Choose Category</option>
                        <option value='all'>All Categories</option>
                        <option value='basic'>Basic</option>
                        <option value='prime'>Prime</option>
                        <option value='gold'>Gold</option>
                    </select>
                </td>
            </form>
        </tr>
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" value="See All Outstanding Accounts" name="seeOutstanding" style="min-width: 170px">
                </td>
            </form>
        </tr>
    </table>
    <div>
        <span class="help-block" style="color: green"><?php echo $deactivate_result; ?></span>
    </div>
</td>
</tr>
<tr>
    <td></td>
    <td style="text-align: center">
        <div id="longJobDescription"></div>
        <form id='jobApplyForm' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="visibility: hidden" method="post">
            <div class="form-group">
                <input type="text" style="visibility: hidden" id= 'appliedJob' name="appliedJob" value="">
                <br>
                <input type="submit" class="btn btn-primary" value="Apply" align="left">
            </div>

        </form>
    </td>
</tr>
</table>
</BODY>
</HTML>
