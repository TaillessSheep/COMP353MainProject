<?php
require 'config.php';
session_start();
$accountID=$_SESSION['accountID'];
// Processing form data when form is submitted

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
                <th>Category</th>
                <th>Post Date</th>
            </tr>
            </thead>
            <tfoot>

            </tfoot>
            <tbody id="tableBody">
            <?php
            $sql = "SELECT jobID,title,briefDescription,postDate,category FROM 1Job where employerID = '$accountID' ";
            $result = mysqli_query($db,$sql);
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row['jobID']."</td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['briefDescription']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['postDate']."</td>";
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
        <form id='jobAppliedForm' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="visibility: hidden" method="post">
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
