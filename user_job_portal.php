<?php
require 'config.php';
session_start();
$appliedJob=$accountID=$appliedJob_err=$apply_result="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    // Validate jobID
    if (empty(trim($_POST["appliedJob"])))
    {
        $appliedJob_err = "There was an error applying to the job";
    } else
    {
        $appliedJob = trim($_POST["appliedJob"]);
    }
    // Validate ID
    if (empty(trim($_SESSION["accountID"])))
    {
        $appliedJob_err = "There was an error applying to the job";
    } else
    {
        $accountID = trim($_SESSION["accountID"]);
    }
    if (empty($appliedJob_err))
    {
        $sql = "INSERT INTO `1Applied` (jobID, jobSeekerID,status) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($db, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_jobID, $param_accountID, $param_status);

            // Set parameters
            $param_jobID = $appliedJob;
            $param_accountID = $accountID;
            $param_status = 1;

            if (mysqli_stmt_execute($stmt))
            {
                $apply_result = 'Your have sucessfully applied to job #.'.$appliedJob;
            } else
            {
                $appliedJob_err = "Something went wrong. You may have already applied to this job.";
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

<table style="width: 100%;">
    <tr>
        <td style="text-align: center;" >
            <table class="blueTable" style="margin-left: 3%"">
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
            <tfoot>

            </tfoot>
            <tbody id="tableBody">
            <?php
            $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job";
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
    <div>
        <button onclick="searchById()">Search job by ID </button>
        <input type="text" id="jobID">
        <br>
        <br>
        <button onclick="searchByCategory()">Search job by category </button>
        <select id="category" size="1">
            <option value=''hidden>Choose Category</option>
            <option value=''>All Categories</option>
            <?php
            $sql = "SELECT category FROM 1Job";
            $result = mysqli_query($db,$sql);
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value='".$row['category']."'>".$row['category']."</option>";
            }
            ?>
        </select>
        <br>
    </div>
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
<br>

</BODY>
</HTML>
