<?php

// 创建连接
$servername = "******";
$username = "*****";
$password = "******";
$dbname = "*****";
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$jobID = $employerID = $title = $briefDescription
    = $description = $requirements = $amountNeeded
    = $postDate = $endingDate = $category = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $jobID = $_POST['jobID'];
    $employerID = $_POST['employerID'];
    $title = $_POST['title'];
    $sql = "INSERT INTO `1job` (jobID, employerID,title) values ('$jobID','$employerID','$title')";
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
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </form>
</div>
</body>
</html>

