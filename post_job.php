<?php


$servername = "localhost";
$username = "root";
$password = "Aa990205qzr+++";
$dbname = "girrafe";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("connect failed: " . $conn->connect_error);
}
$jobID = $employerID = $title = $briefDescription
    = $description = $requirements = $amountNeeded
    = $postDate = $endingDate = $category = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $jobID = $_POST['jobID'];
    $employerID = $_POST['employerID'];
    $title = $_POST['title'];
    $briefDescription = $_POST['briefDescription'];
    $description = $_POST['Description'];
    $requirements = $_POST['requirements'];
    $amountNeeded = $_POST['amountNeeded'];
    $postDate = $_POST['postDate'];
    $endingDate = $_POST['endingDate'];
    $category = $_POST['category'];
    $sql = "INSERT INTO `1job` (jobID, employerID,title, briefDescription, description, requirements, amountNeeded, postDate, endingDate, category) 
values ('$jobID','$employerID','$title','$briefDescription','$description', '$requirements', '$amountNeeded','$postDate','$endingDate','$category')";
    if ($conn->query($sql) === TRUE) {
        echo "Record has been saved!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <form name='submitform' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group ">
            <label>Job ID</label>
            <input type="text" name="jobID" class="form-control" value="<?php echo $jobID; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>Employer ID</label>
            <input type="text" name="employerID" class="form-control" value="<?php echo $employerID; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>BriefDescription</label>
            <input type="text" name="briefDescription" class="form-control" value="<?php echo $briefDescription; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>Description</label>
            <input type="text" name="Description" class="form-control" value="<?php echo $description; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>requirements</label>
            <input type="text" name="requirements" class="form-control" value="<?php echo $requirements; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>amountNeeded</label>
            <input type="text" name="amountNeeded" class="form-control" value="<?php echo $amountNeeded; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>postDate</label>
            <input type="text" name="postDate" class="form-control" value="<?php echo $postDate; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>endingDate</label>
            <input type="text" name="endingDate" class="form-control" value="<?php echo $endingDate; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group ">
            <label>category</label>
            <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
            <span class="help-block"></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </form>
</div>
</body>
</html>

