<?php
require 'config.php';
// Define variables and initialize with empty values
$title=$briefDescription=$description=$requirements=$amountNeeded=$endingDate ='';
$title_err=$briefDescription_err=$description_err=$requirements_err=$amountNeeded_err=$endingDate_err= $category_err='';
$update_result="";
session_start();
// Processing form data when form is submitted
if(isset($_SERVER["REQUEST_METHOD"]) and $_SERVER["REQUEST_METHOD"] == "POST")
{
    $title = trim($_POST['title']);
    $briefDescription = trim($_POST['briefDescription']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);
    $amountNeeded = trim($_POST['amountNeeded']);
    $endingDate = trim($_POST['endingDate']);
    $category = trim($_POST['category']);

    

//    echo $title.$briefDescription.$description.$requirements.$amountNeeded.$endingDate.$category;

    $sql = "INSERT INTO 1Job (employerID, title, briefDescription, description, requirements, amountNeeded,postDate,endingDate,category)
                    VALUES (?,?,?,?,?,?,?,?,?)";
    if ($stmt = mysqli_prepare($db, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssss",
            $param_employerID,
            $param_title,
            $param_briefDescription,
            $param_description,
            $param_requirements,
            $param_amountNeeded,
            $param_postDate,
            $param_endingDate,
            $param_category);
        // Set parameters
        $param_employerID       = $_SESSION['accountID'];
        $param_title            = $title;
        $param_briefDescription = $briefDescription;
        $param_description      = $description;
        $param_requirements     = $requirements;
        $param_amountNeeded     = $amountNeeded;
        $param_postDate         = date('y-m-d');
        $param_endingDate       = $endingDate;
        $param_category         = $category;

        mysqli_stmt_execute($stmt);
        echo $stmt->error;
        mysqli_stmt_close($stmt);

        echo '<script type="text/javascript">';
        echo "alert('You have successfully posted a new job.');";
        echo 'window.location.href = "employer_dashboard.php";';
        echo '</script>';
//        header("location: " . $lastPage);
    }



}
?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</HEAD>

<BODY>
<?php require 'employer_dashboard_navbar.php' //nav bar
?>
<h2 style=" padding-left: 25px;" >Post New Job</h2>
<div class="wrapper" style="width: 90%; padding-left: 25px;" >
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>" style="width: 70mm">
            <label>Title</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   placeholder="No more than 20 characters"
                   value="<?php echo $title; ?>"
                   required>
            <span class="help-block"><?php echo $title_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($briefDescription_err)) ? 'has-error' : ''; ?>">
            <label>Brief Description</label>
            <input type="text"
                   name="briefDescription"
                   class="form-control"
                   style="width: 180mm; "
                   placeholder="No more than 50 characters"
                   value="<?php echo $briefDescription; ?>"
                   required>
            <span class="help-block"><?php echo $briefDescription_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>" style="width: 80%;">
            <label>Description</label> <br>
            <div>
                <textarea name="description"
                          cols="90"
                          rows="7"
                          placeholder="No more than 2000 characters"
                          required><?php echo $description; ?></textarea>
            </div>
            <span class="help-block"><?php echo $description_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($requirements_err)) ? 'has-error' : ''; ?>">
            <label>Requirements</label>
            <input type="text"
                   name="requirements"
                   class="form-control"
                   value="<?php echo $requirements; ?>"
                   style="width: 100mm; "
                   placeholder="No more than 30 characters">
            <span class="help-block"><?php echo $requirements_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($amountNeeded_err)) ? 'has-error' : ''; ?>">
            <label>Amount Needed</label>
            <input type="number" min="1" name="amountNeeded" class="form-control" style="width: 3cm" value="<?php echo $amountNeeded; ?>" required>
            <span class="help-block"><?php echo $amountNeeded_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($endingDate_err)) ? 'has-error' : ''; ?>">
            <label>The Post End On</label>
            <input type="date" name="endingDate" class="form-control" style="width: 45mm" value="<?php echo $endingDate; ?>">
            <span class="help-block"><?php echo $endingDate_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($category_err)) ? 'has-error' : ''; ?>">
            <label>Category</label>
            <select name="category" id="category" size="1" style="min-width: 100px">
                <?php
                $sql = "SELECT categoryName AS category 
                                FROM 1JobCategory";
                $result = mysqli_query($db,$sql);
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='".$row['category']."'>".$row['category']."</option>";
                }
//                    echo "<option value='Auto Pay'SELECTED>Auto Pay</option>";
//                    echo "<option value='Manual'>Manual Pay</option>";
                ?>
            </select>
            <span class="help-block"><?php echo $category_err; ?></span>
        </div>


        <br>
<!--        <h3>Change Account Category-->
<!--            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><i class="material-icons">info</i></button>-->
<!--        </h3>-->

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
<!--            <input type="reset" class="btn btn-default" value="Reset">-->
        </div>
    </form>
</div>


</div>
</BODY>
</HTML>
