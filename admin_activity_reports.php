<?php
require 'config.php';
session_start();
$deleteJobResult= $deleteApplicationResult="";
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST['deleteJob']))
    {
        $jobID = $_POST['deleteJobID'];
        $sql = "DELETE FROM `1Job` WHERE jobID='".$jobID."'";
        if(mysqli_query($db,$sql)){
            $deleteJobResult='You have sucessfully deleted job id# '.$jobID.'.';
        }
    }
    if(isset($_POST['deleteApplication']))
    {
        $application=$_POST['deleteApplicationID'];
        $accountID = explode("#",$application)[0];
        $jobID = explode("#",$application)[1];
        $sql = "DELETE FROM `1Applied` WHERE jobID='".$jobID."' AND jobSeekerID='".$accountID."'";
        if(mysqli_query($db,$sql)){
            $deleteApplicationResult='You have sucessfully deleted this application id#';
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
<?php require 'admin_dashboard_navbar.php' //nav bar
?>
<H1>Activity Overview</H1>
<br><br>
<h2>Posted Jobs</h2>
<table style="width: 100%;">
    <tr>
        <td style="vertical-align: top">
            <table class="blueTable" style="margin-left: 3%;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Employer</th>
                    <th>Category</th>
                    <th>Post Date</th>
                    <th>Details</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php
                //Reset table to all applications
                if(isset($_POST['resetTable']))
                {
                    $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job";
                    $result = mysqli_query($db,$sql);
                }
                //Search by ID a job
                elseif(isset($_POST['searchID']))
                {
                    $jobID = $_POST['jobID'];
                    if(empty($jobID))
                    {
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job";
                    }
                    else
                    {
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                            WHERE jobID='".$jobID."'";
                    }

                    $result = mysqli_query($db,$sql);
                }
                // Search jobs by category
                elseif(isset($_POST['searchCategory']))
                {
                    $category = $_POST['category'];
                    if($category == 'all' || empty($category))
                    {
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job";
                    }
                    else
                    {
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                            WHERE category='".$category."'";
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
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job";
                    }
                    // no lower date limit
                    elseif(empty($fromDate))
                    {
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                            WHERE postDate<='".$toDate."'";
                    }
                    // no upper date limit
                    elseif(empty($toDate))
                    {
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                            WHERE postDate>='".$fromDate."'";
                    }
                    // 2 side bounded date span
                    else{
                        $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job
                            WHERE postDate>='".$fromDate."'AND postDate<='".$toDate."'";
                    }
                    $result = mysqli_query($db,$sql);
                }
                //Default table. All jobs
                else{
                    $sql = "SELECT jobID,title,briefDescription,postDate,category,description,requirements,amountNeeded,endingDate,employerID FROM 1Job";
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
                    echo "<td style='text-align: center;'>
                        <form method='post'>
                        <input type='submit' value='Delete' name='deleteJob'>    
                        <input type='hidden' value='".$row['jobID']."' name='deleteJobID'>
                        </form>
                         </td>";
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
                                $sql = "SELECT category 
                                    FROM 1Job J, 1Applied A
                                    WHERE J.jobID = A.jobID";
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
                <span class="help-block" style="color: green"><?php echo $deleteJobResult ?></span>
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

<br>
<h2>Posted Applications</h2>
<table style="width: 100%;">
    <tr>
        <td style="vertical-align: top">
            <table class="blueTable" style="margin-left: 3%;">
                <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Job Seeker ID</th>
                    <th>Status</th>
                    <th>Job Description</th>
                    <th>Category</th>
                    <th>Applied On Date</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                <?php
                //Reset table to all applications
                if(isset($_POST['resetTableApplied']))
                {
                    $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID";
                    $result = mysqli_query($db,$sql);
                }
                //Search by Job ID a job
                elseif(isset($_POST['searchJobIDApplied']))
                {
                    $jobID = $_POST['jobIDApplied'];
                    if(empty($jobID))
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID";
                    }
                    else
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID
                            AND A.jobID='" . $jobID ."'";;
                    }
                    $result = mysqli_query($db,$sql);
                }
                //Search by Account ID
                elseif(isset($_POST['searchAccountIDApplied'])){
                    $accountID = $_POST['accountIDApplied'];
                    if(empty($accountID))
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID";
                    }
                    else
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID
                            AND A.jobSeekerID='" . $accountID ."'";;
                    }
                    $result = mysqli_query($db,$sql);
                }
                // Search jobs by category
                elseif(isset($_POST['searchCategoryApplied']))
                {
                    $category = $_POST['categoryApplied'];
                    if($category == 'all' || empty($category))
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID";
                    }
                    else
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID
                            AND J.category='" . $category ."'";
                    }
                    $result = mysqli_query($db,$sql);
                }
                //Search jobs by post date
                elseif(isset($_POST['searchDateApplied']))
                {
                    $fromDate = $_POST['date_fromApplied'];
                    $toDate = $_POST['date_toApplied'];
                    //No date limit
                    if(empty($fromDate) and empty($toDate))
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID";
                    }
                    // no lower date limit
                    elseif(empty($fromDate))
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID
                            AND A.appliedOn<'". $toDate ."'";
                    }
                    // no upper date limit
                    elseif(empty($toDate))
                    {
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID
                            AND A.appliedOn>'". $fromDate ."'";
                    }
                    // 2 side bounded date span
                    else{
                        $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID
                            AND A.appliedOn>'". $fromDate ."'
                            AND A.appliedOn<'". $toDate ."'";
                    }
                    $result = mysqli_query($db,$sql);
                }
                //Default table. All jobs
                else{
                    $sql = "SELECT A.jobID, A.jobSeekerID,A.status,J.briefDescription,J.category,A.appliedOn 
                            FROM `1Applied` A,`1Job` J 
                            WHERE A.jobID=J.jobID";
                    $result = mysqli_query($db,$sql);
                }

                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <script> var jobdata = <?php echo json_encode($row);?></script>
                    <?php
                    echo "<tr>";
                    echo "<td>".$row['jobID']."</td>";
                    echo "<td>".$row['jobSeekerID']."</td>";
                    echo "<td>".$row['status']."</td>";
                    echo "<td>".$row['briefDescription']."</td>";
                    echo "<td>".$row['category']."</td>";
                    echo "<td>".$row['appliedOn']."</td>";
                    echo "<td style='text-align: center;'>
                        <form method='post'>
                        <input type='submit' value='Delete' name='deleteApplication'>    
                        <input type='hidden' value='".$row['jobSeekerID']."#".$row['jobID']."' name='deleteApplicationID'>
                        </form>
                         </td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <div>
                <span class="help-block" style="color: green"><?php echo $deleteApplicationResult ?></span>
            </div>
        </td>
        <td style="text-align: center">
            <table style="margin-left: 50px; border-spacing: 150px; text-align: left">
                <tr>
                    <form method="post">
                        <td style="padding-bottom: 1em;">
                            <input type="submit" value="Reset" name="resetTableApplied" style="min-width: 170px">
                        </td>
                        <td style="padding-bottom: 1em; padding-left: 1em">
                        </td>
                    </form>
                </tr>
                <tr>
                    <form method="post">
                        <td style="padding-bottom: 1em;">
                            <input type="submit" value="Search By Job ID" name="searchJobIDApplied" style="min-width: 170px">
                        </td>
                        <td style="padding-bottom: 1em; padding-left: 1em">
                            <input type="text"  name="jobIDApplied"  style="min-width: 150px">
                        </td>
                    </form>
                </tr>
                <tr>
                    <form method="post">
                        <td style="padding-bottom: 1em;">
                            <input type="submit" value="Search By Account ID" name="searchAccountIDApplied" style="min-width: 170px">
                        </td>
                        <td style="padding-bottom: 1em; padding-left: 1em">
                            <input type="text"  name="accountIDApplied"  style="min-width: 150px">
                        </td>
                    </form>
                </tr>
                <tr>
                    <form method="post">
                        <td style="padding-bottom: 1em;">
                            <input type="submit" value="Search By Job Category" name="searchCategoryApplied" style="min-width: 170px">
                        </td>
                        <td style="padding-bottom: 1em;padding-left: 1em">
                            <select name="categoryApplied" id="category" size="1" style="min-width: 150px">
                                <option value=''hidden>Choose Category</option>
                                <option value='all'>All Categories</option>
                                <?php
                                $sql = "SELECT category 
                                    FROM 1Job J, 1Applied A
                                    WHERE J.jobID = A.jobID";
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
                            <input type="submit" name="searchDateApplied" value="Search By DateApplied" style="min-width: 170px">
                        </td>
                        <td style="padding-bottom: 1em;padding-left: 1em">
                            <label>From</label>
                            <input type="date" name="date_fromApplied" style="min-width: 150px">
                            <label>To</label>
                            <input type="date" name= "date_toApplied" style="min-width: 150px">
                        </td >
                    </form>
                </tr>
            </table>
        </td>
    </tr>
</table>

</BODY>
</HTML>
