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
<?php require 'user_dashboard_navbar.php' //nav bar
?>
<H1>Employers </H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;vertical-align: top"" >
        <table class="blueTable" style="margin-left: 3%;width: 75%">
            <thead>
            <tr>
                <th>Employer</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Number of Jobs</th>
                <th>Jobs</th>
                <th>Contact</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            <?php
            $accountID=$_SESSION['accountID'];
            $sql = "SELECT accountID,phone, email
                    FROM 1User U
                    WHERE U.isEmployer = 1";
            $result = mysqli_query($db,$sql);
            while ($row = mysqli_fetch_array($result)) {
                $accountID = $row['accountID'];
                $sql = "SELECT COUNT(*) as numberOfJobs
                        FROM 1User U, 1Job J
                        WHERE U.accountID=J.employerID AND J.employerID = '$accountID'";
                $result2 = mysqli_query($db,$sql);
                $row2 = mysqli_fetch_array($result2);
                echo "<tr>";
                echo "<td>".$row['accountID']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row2['numberOfJobs']."</td>";
                echo "<td><a href='user_employer_jobs.php?EID=".$row['accountID']."'>See Their Jobs</a></td>";
                echo "<td><a href='user_contact_employer.php?EID=".$row['accountID']."'>Contact this Employer</a></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        </td>
    </tr>

</table>
</BODY>
</HTML>
