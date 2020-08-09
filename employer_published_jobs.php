<?php
require 'config.php';
session_start();
$accountID=$deleteJobResult=$appliedJob_err="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST['deleteJob']))
    {

        $jobID = $_POST['deleteJobID'];
        echo $jobID;
        $sql = "DELETE FROM `1Job` WHERE jobID='$jobID'";
        if(mysqli_query($db,$sql)){
            $deleteJobResult='You have sucessfully deleted job id# '.$jobID.'.';
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
<?php require 'employer_dashboard_navbar.php' //nav bar
?>
<H1>My Published Jobs</H1>
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;vertical-align: top"" >
        <table class="blueTable" style="margin-left: 3%;"">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Category</th>
            <th>Post Date</th>
            <th>End Date</th>
            <th>Details</th>
            <th># of Applicants</th>
            <th>Applications</th>
            <th>Delete Job</th>
        </tr>
        </thead>
        <tbody id="tableBody">
        <?php
        $accountID=$_SESSION['accountID'];
        //Reset table to all applications
        if(isset($_POST['resetTable']))
        {
            $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."'";
            $result = mysqli_query($db,$sql);
        }
        //Search by ID a job
        elseif(isset($_POST['searchID']))
        {
            $jobID = $_POST['jobID'];
            if(empty($jobID))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."'";
            }
            else
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."' AND jobID='".$jobID."'";
            }

            $result = mysqli_query($db,$sql);
        }
        // Search jobs by category
        elseif(isset($_POST['searchCategory']))
        {
            $category = $_POST['category'];
            if($category == 'all' || empty($category))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."'";
            }
            else
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."' AND category='".$category."'";
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
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."'";
            }
            // no lower date limit
            elseif(empty($fromDate))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."' AND postDate<='".$toDate."'";
            }
            // no upper date limit
            elseif(empty($toDate))
            {
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."' AND postDate>='".$fromDate."'";
            }
            // 2 side bounded date span
            else{
                $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID  
                        FROM 1Job
                        WHERE employerID='".$accountID."' AND postDate>='".$fromDate."'AND postDate<='".$toDate."'";
            }
            $result = mysqli_query($db,$sql);
        }
        //Default table. All jobs
        else{
            $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,endingDate,employerID   
                        FROM 1Job
                        WHERE employerID='".$accountID."'";
            $result = mysqli_query($db,$sql);
        }

        while ($row = mysqli_fetch_array($result)) {
            ?>
            <script> var jobdata = <?php echo json_encode($row);?></script>
            <?php
            $jobID=$row['jobID'];
            $sql = "SELECT COUNT(*) as numberOfApplications FROM `1Applied` WHERE jobID= '$jobID'";
            $result2 = mysqli_query($db,$sql);
            $row2 = mysqli_fetch_array($result2);
            echo "<tr>";
            echo "<td>".$row['jobID']."</td>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['briefDescription']."</td>";
            echo "<td>".$row['category']."</td>";
            echo "<td>".$row['postDate']."</td>";
            echo "<td>".$row['endingDate']."</td>";
            echo "<td>".
                "<div hidden id='data".$row['jobID']."'>".json_encode($row,JSON_PRETTY_PRINT)."</div>".
                "<button class='expandable' id ='button".$row['jobID']."'style='border: none' value='".$row['jobID']."' onclick='getMoreJobInfo(this.value)'>".
                "<i class=\"material-icons\">expand_more</i>".
                "More</button>".
                "</td>";
            echo "<td>".$row2['numberOfApplications']."</td>";
            echo "<td><a href='employer_job_applications.php?jobID=".$row['jobID']."'>See Applications</a></td>";
            echo "<td style='text-align: center;'>
                        <form method='post'>
                        <input type='submit' value='Delete' name='deleteJob'>    
                        <input type='hidden' value='".$row['jobID']."' name='deleteJobID'>
                        </form>
                         </td>";
            echo "</tr>";
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
                    <input type="submit" name="searchDate" value="Search By Post Date" style="min-width: 170px">
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
        <span class="help-block" style="color: green"><?php echo $deleteJobResult; ?></span>
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
