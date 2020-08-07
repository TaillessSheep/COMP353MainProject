<?php
require 'config.php';
session_start();
// Processing form data when form is submitted
$deactivate_result = $deactivate_err="";
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{

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
<?php require 'admin_dashboard_navbar.php' //nav bar
?>
<H1>User Reports</H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;" >
            <table class="blueTable" style="margin-left: 3%;"">
            <thead>
            <tr>
                <th>Account ID</th>
                <th>Account Type</th>
                <th>Account Category</th>
                <th>Charge</th>
                <th>Status</th>
                <th>Email</th>
                <th>Balance</th>
                <th>Deactivate</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            <?php
            //Reset table to all applications
            if(isset($_POST['resetTable']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance FROM oyc353_1.`1User`";
                $result = mysqli_query($db,$sql);
            }
            // See Employers
            elseif(isset($_POST['seeEmployers']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM oyc353_1.`1User`
                        WHERE isEmployer='" . 1 ."'";
                $result = mysqli_query($db,$sql);
            }
            //See JS
            elseif(isset($_POST['seeJS']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM oyc353_1.`1User`
                        WHERE isEmployer='" . 0 ."'";
                $result = mysqli_query($db,$sql);
            }
            // Search by premium option
            elseif(isset($_POST['filterPremium']))
            {
                $category = $_POST['accountCategory'];
                if($category == 'all' || empty($category))
                {
                    $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance FROM oyc353_1.`1User`";
                }
                else
                {
                    $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM oyc353_1.`1User`
                        WHERE premiumOpt='" . $category ."'";
                }
                $result = mysqli_query($db,$sql);
            }
            //See outstanding balance accounts
            elseif(isset($_POST['seeOutstanding']))
            {
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance 
                        FROM oyc353_1.`1User`
                        WHERE balance<'" . 0 ."'";
                $result = mysqli_query($db,$sql);
            }
            //Default table. All users
            else{
                $sql = "SELECT accountID,isEmployer,premiumOpt,charge,status,email,balance FROM oyc353_1.`1User`";
                $result = mysqli_query($db,$sql);
            }

            $result = mysqli_query($db,$sql);
            while ($row = mysqli_fetch_array($result)) {
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
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['balance']."</td>";
                echo "<td>Deactivate</td>";
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
        <span class="help-block"><?php echo $deactivate_err; ?></span>
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
