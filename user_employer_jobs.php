<?php
require 'config.php';
session_start();
$appliedJob=$accountID=$appliedJob_err=$apply_result="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST["appliedJob"]))
    {
        // Validate jobID
        if (empty(trim($_POST["appliedJob"])))
        {
            $appliedJob_err = "There was an error applying to the job";
        } else
        {
            $appliedJob = trim($_POST["appliedJob"]);
        }

        $accountID = trim($_SESSION["accountID"]);
        //Verify if user can apply to this job given his account category
        $sql = "SELECT COUNT(*) AS total_applications FROM `1Applied` WHERE jobSeekerID = '$accountID'";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        $sql = "SELECT premiumOpt FROM `1User` WHERE accountID = '$accountID'";
        $result2 = mysqli_query($db, $sql);
        $row2 = mysqli_fetch_array($result2);
        if ($row2['premiumOpt'] == 'basic')
        {
            $appliedJob_err = "You cannot apply to job with a basic account. Upgrade your account to apply to this job!";
        } elseif ($row2['premiumOpt'] == 'prime' && $row['total_applications'] > 5)
        {
            $appliedJob_err = "You have already applied to 5 jobs. Upgrade your account to apply to this job!";
        }

        if (empty($appliedJob_err))
        {
            $sql = "INSERT INTO `1Applied` (jobID, jobSeekerID,status,appliedOn) VALUES (?, ?, ?,?)";
            if ($stmt = mysqli_prepare($db, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_jobID, $param_accountID, $param_status,$param_appliedOn);

                // Get Date
                $date = new DateTime('now');
                $str_date=date_format($date, 'Y-m-d');
                // Set parameters
                $param_jobID = $appliedJob;
                $param_accountID = $accountID;
                $param_status = 'pending';
                $param_appliedOn=$str_date;

                if (mysqli_stmt_execute($stmt))
                {
                    $apply_result = 'Your have sucessfully applied to job #.' . $appliedJob;
                } else
                {
                    $appliedJob_err = "Something went wrong. You may have already applied to this job.";
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
<?php require 'user_dashboard_navbar.php' //nav bar
?>
<H1>Job Portal</H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;vertical-align: top"" >
        <table class="blueTable" style="margin-left: 3%;"">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Employer</th>
            <th>Category</th>
            <th>Post Date</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody id="tableBody">
        <?php
        $EID = $_GET['EID'];
        //Reset table to all applications
        if(isset($_POST['resetTable']))
        {
            $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job WHERE employerID = '$EID'";
            $result = mysqli_query($db,$sql);
        }
        //Search by ID a job
        elseif(isset($_POST['searchID']))
        {
            $jobID = $_POST['jobID'];
            if(empty($jobID))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job WHERE employerID = '$EID'";
            }
            else
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                        WHERE jobID='".$jobID."' AND employerID = '$EID'";
            }

            $result = mysqli_query($db,$sql);
        }
        // Search jobs by category
        elseif(isset($_POST['searchCategory']))
        {
            $category = $_POST['category'];
            if($category == 'all' || empty($category))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job WHERE employerID = '$EID'";
            }
            else
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                        WHERE category='".$category."' AND employerID = '$EID'";
            }
            $result = mysqli_query($db,$sql);
        }
        //Search jobs by post date
        elseif(isset($_POST['searchDate']))
        {
            $fromDate = $_POST['date_from'];
            $toDate = $_POST['date_to'];
            //No date limit
            if(empty($fromDate) and empty($toDate))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job WHERE employerID = '$EID'";
            }
            // no lower date limit
            elseif(empty($fromDate))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                        WHERE postDate<='".$toDate."'AND employerID = '$EID'";
            }
            // no upper date limit
            elseif(empty($toDate))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                        WHERE postDate>='".$fromDate."' AND employerID = '$EID'";
            }
            // 2 side bounded date span
            else{
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                        WHERE postDate>='".$fromDate."'AND postDate<='".$toDate."' AND employerID = '$EID'";
            }
            $result = mysqli_query($db,$sql);
        }
        //Default table. All jobs
        else{
            $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job WHERE employerID = '$EID'";
            $result = mysqli_query($db,$sql);
        }

        $result = mysqli_query($db,$sql);
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
            echo "<td>".$row['postDate']."</td>";
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
        <span class="help-block"><?php echo $appliedJob_err; ?></span>
        <span class="help-block" style="color: green"><?php echo $apply_result; ?></span>
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
