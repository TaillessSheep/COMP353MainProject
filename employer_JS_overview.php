<?php
require 'config.php';
session_start();
// Processing form data when form is submitted
$deactivate_result='';
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
<?php require 'employer_dashboard_navbar.php' //nav bar
?>
<H1>Applicant Reports</H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;" >
            <table class="blueTable" style="margin-left: 3%;width: 75%">
                <thead>
                <tr>
                    <th>Account ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Number of Applications</th>
                    <th>Applications</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php
                $accountID=$_SESSION['accountID'];
                $sql = "SELECT DISTINCT A.jobSeekerID,U.phone,U.email,Ac.profileName,COUNT(A.jobID) as numberApplication 
                    FROM 1Applied A,1Job J, 1User U,1Account Ac 
                    WHERE A.jobSeekerID = U.accountID AND Ac.accountID=U.accountID AND A.jobID = J.jobID AND J.employerID='".$accountID."'";
                $result = mysqli_query($db,$sql);
                echo $db->error;
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$row['jobSeekerID']."</td>";
                    echo "<td>".$row['profileName']."</td>";
                    echo "<td>".$row['phone']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['numberApplication']."</td>";
                    echo "<td><a href='employer_JS_applications.php?JSID=".$row['jobSeekerID']."'>See Their Applications</a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </td>
        <td style="text-align: center">
            <div>
                <span class="help-block" style="color: green"><?php echo $deactivate_result; ?></span>
            </div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center">
            <div id="longJobDescription"></div>
        </td>
    </tr>
</table>
</BODY>
</HTML>
