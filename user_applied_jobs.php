<?php
require 'config.php';
session_start();
$result; //MySQL query result
$unappliedJob=$accountID=$unappliedJob_err=$unapply_result="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    //Accept or refuse offer
    if(isset($_POST['offerDecision']))
    {
        $accountID=$_SESSION['accountID'];
        $decisionString=$_POST['offerDecision'];
        $decision = explode("#",$decisionString)[0];
        $jobID = explode("#",$decisionString)[1];
        $sql = "UPDATE 1Applied SET status='".$decision."' WHERE jobID='".$jobID."' AND jobSeekerID='".$accountID."'";
        if(mysqli_query($db,$sql))
        {
            if($decision=='accept')
            {
                $unapply_result='You have succesfully accepted this offer! The employer will contact you shortly.';
            }
            elseif($decision=='refuse')
            {
                $unapply_result='You have succesfully declined this offer! The employer will contact you shortly.';
            }

        }
        else
        {
            $unapply_result='Something went wrong. Offer was not accepted. Please try again later';
        }
    }
    //Application to a job
    if(isset($_POST['appliedJob']))
    {
        // Validate jobID
        if (empty(trim($_POST["appliedJob"])))
        {
            $unappliedJob_err = "There was an error removing application to the job";
        } else
        {
            $unappliedJob = trim($_POST["appliedJob"]);
        }
        // Validate ID
        if (empty(trim($_SESSION["accountID"])))
        {
            $unappliedJob_err = "There was an error removing application to the job";
        } else
        {
            $accountID = trim($_SESSION["accountID"]);
        }
        if (empty($unappliedJob_err))
        {
            $sql = "DELETE FROM `1Applied` WHERE jobID= ? AND jobSeekerID = ?";
            if ($stmt = mysqli_prepare($db, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_jobID, $param_accountID);

                // Set parameters
                $param_jobID = $unappliedJob;
                $param_accountID = $accountID;

                if (mysqli_stmt_execute($stmt))
                {
                    $unapply_result = 'Your have sucessfully withdrawn you application to job #.' . $unappliedJob;
                } else
                {
                    $unappliedJob_err = "Something went wrong. Please try again later.";
                }
            }
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
<?php require 'user_dashboard_navbar.php'; //nav bar
?>

<H1>My applications</H1>

<table style="width: 100%;">
    <tr>
        <td style="text-align: center; vertical-align: top"" >
            <table class="blueTable" style="margin-left: 3%"">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Employer</th>
                <th>Category</th>
                <th>Application Date</th>
                <th>Application Status</th>
                <th>Details</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            <?php
            //Reset table to all applications
            if(isset($_POST['resetTable']))
            {
                $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status 
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID";
                $result = mysqli_query($db,$sql);
            }
            //Search by ID a job
            elseif(isset($_POST['searchID']))
            {
                $jobID = $_POST['jobID'];
                if(empty($jobID))
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID";
                }
                else
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID AND J.jobID='".$jobID."'";
                }

                $result = mysqli_query($db,$sql);
            }
            // Search applications by category
            elseif(isset($_POST['searchCategory']))
            {
                $category = $_POST['category'];
                if($category == 'all' || empty($category))
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID";
                }
                else
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID AND category='".$category."'";
                }
                $result = mysqli_query($db,$sql);
            }
            //Search jobs by application date
            elseif(isset($_POST['searchDate']))
            {
                $fromDate = $_POST['date_from'];
                $toDate = $_POST['date_to'];
                //No date limit
                if(empty($fromDate) and empty($toDate))
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID ";
                }
                // no lower date limit
                elseif(empty($fromDate))
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID AND appliedOn<='".$toDate."'";
                }
                // no upper date limit
                elseif(empty($toDate))
                {
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID AND appliedOn>='".$fromDate."'";
                }
                // 2 side bounded date span
                else{
                    $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID AND appliedOn>='".$fromDate."'AND appliedOn<='".$toDate."'";
                }
                $result = mysqli_query($db,$sql);
            }
            //Default table. All applications
            else{
                $sql = "SELECT J.jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID,appliedOn,A.status  
                        FROM 1Job J, `1Applied` A
                        WHERE J.jobID = A.jobID";
                $result = mysqli_query($db,$sql);
            }

            while ($row = mysqli_fetch_array($result)) {
                ?>
                <script> var jobdata = <?php echo json_encode($row);?></script>
                <?php
                echo "<tr>";
                echo "<td>".$row['jobID']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['briefDescription']."</td>";
                echo "<td>".$row['employerID']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['appliedOn']."</td>";
                if($row['status']=='offer')
                {
                    echo "<td>You have received an offer! 
                           <form method='post'>
                           <select name='offerDecision'>
                           <option value=''hidden>Decision</option>
                           <option value='accept#".$row['jobID']."'>Accept Offer</option>
                           <option value='refuse#".$row['jobID']."''>Refuse Offer</option>
                            </select>
                            <input type='submit' value='Send'>
                            </form>
                           </td>";
                }
                else{
                    echo "<td>".$row['status']."</td>";
                }
                echo "<td>".
                    "<div hidden id='data".$row['jobID']."'>".json_encode($row,JSON_PRETTY_PRINT)."</div>".
                    "<button class='expandable' id ='button".$row['jobID']."'style='border: none' value='".$row['jobID']."' onclick='getMoreJobInfo(this.value)'>".
                    "<i class=\"material-icons\">expand_more</i>".
                    "More</button>".
                    "</td>";
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
                    <input type="submit" value="Search By ID" name="searchID" style="min-width: 170px">
                </td>
                <td style="padding-bottom: 1em; padding-left: 1em">
                    <input type="text"  name="jobID" id="jobID" style="min-width: 150px">
                </td>
            </form>
        </tr>
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" value="Search By Category" name="searchCategory" style="min-width: 170px">
                </td>
                <td style="padding-bottom: 1em;padding-left: 1em">
                    <select name="category" id="category" size="1" style="min-width: 150px">
                        <option value=''hidden>Choose Category</option>
                        <option value='all'>All Categories</option>
                        <?php
                        $sql = "SELECT categoryName AS category 
                                FROM 1JobCategory";
                        $result = mysqli_query($db,$sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='".$row['category']."'>".$row['category']."</option>";
                        }
                        ?>
                    </select>
                </td>
            </form>
        </tr>
        <tr>
            <form method="post">
                <td style="padding-bottom: 1em;">
                    <input type="submit" name="searchDate" value="Search By Date" style="min-width: 170px">
                </td>
                <td style="padding-bottom: 1em;padding-left: 1em">
                    <label>From</label>
                    <input type="date" name="date_from" id="date_from" style="min-width: 150px">
                    <label>To</label>
                    <input type="date" name= "date_to" id="date_to" style="min-width: 150px">
                </td >
            </form>
        </tr>
    </table>
    <div>
        <span class="help-block"><?php echo $unappliedJob_err; ?></span>
        <span class="help-block" style="color: green"><?php echo $unapply_result; ?></span>
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
                <input type="submit" class="btn btn-primary" value="Remove Application" align="left">
            </div>

        </form>
    </td>
</tr>

</table>
</BODY>
</HTML>
