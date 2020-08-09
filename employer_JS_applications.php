<?php
require 'config.php';
session_start();
// Processing form data when form is submitted
$query_result='';
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST['sendJobOffer']))
    {
        $JSID = $_GET['JSID'];
        $jobID = $_POST['jobID'];
        echo $JSID;
        echo $jobID;
        $sql = "UPDATE 1Applied SET status='offer' WHERE jobID= '$jobID' AND jobSeekerID= '$JSID'";
        if(mysqli_query($db,$sql))
        {
            $query_result='You have succesfully sent an offer.';
        }
    }
    if(isset($_POST['denyApplication']))
    {
        $JSID = $_GET['JSID'];
        $jobID = $_POST['jobID'];
        $sql = "UPDATE 1Applied SET status='deny' WHERE jobID= '$jobID' AND jobSeekerID= '$JSID'";
        if(mysqli_query($db,$sql))
        {
            $query_result='You have succesfully denied this application.';
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
<?php require 'employer_dashboard_navbar.php'; //nav bar
$JSID = $_GET['JSID'];
?>
<H1> Applications of <?php echo $JSID?></H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;" >
            <table class="blueTable" style="margin-left: 3%;"">
            <thead>
            <tr>
                <th>Job ID</th>
                <th>Job Title</th>
                <th>Job Description</th>
                <th>Job Requirements</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Application Date</th>
                <th>Offer</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            <?php
            //Fill table
            $sql = "SELECT Ac.accountID,Ap.jobID,profileName,email,phone,appliedOn,Ap.status,J.title,J.briefDescription,J.requirements 
                        FROM `1Account` Ac,`1User` U,`1Applied` Ap,1Job J
                        WHERE Ac.accountID = U.accountID AND J.jobID=Ap.jobID AND U.accountID = '$JSID' ";
            $result = mysqli_query($db,$sql);

            while ($row = mysqli_fetch_array($result)) {

                echo "<tr>";
                echo "<td>".$row['jobID']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['briefDescription']."</td>";
                echo "<td>".$row['requirements']."</td>";
                echo "<td>".$row['profileName']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['appliedOn']."</td>";
                if($row['status']=='pending')
                {
                    echo "<td>
                        <form method='post'>
                        <input type='submit' value='Send Job Offer' name='sendJobOffer'>    
                        <input type='hidden' value='".$row['jobID']."' name='jobID'>
                        </form>
                        <form method='post'>
                        <input type='submit' value='Deny Application' name='denyApplication'>    
                        <input type='hidden' value='".$row['jobID']."' name='jobID'>
                        </form>
                         </td>";
                }
                elseif($row['status']=='refuse')
                {
                    echo "<td>The Applicant refused the offer</td>";
                }
                elseif($row['status']=='accept')
                {
                    echo "<td>The Applicant accepted the offer!</td>";
                }
                elseif($row['status']=='offer')
                {
                    echo "<td>An offer has been sent to the Applicant.</td>";
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
        </tr>
    </table>
    <div>
        <span class="help-block" style="color: green"><?php echo $query_result?></span>
    </div>
</td>
</tr>
</table>
</BODY>
</HTML>

