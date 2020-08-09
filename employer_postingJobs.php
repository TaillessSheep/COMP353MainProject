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


//    // Username modification
//    if(isset($_POST['old_accountID']))
//    {
//        if(empty(trim($_POST["new_accountID"])))
//        {
//            $new_accountID_err = "Please enter a new username.";
//        } else
//        {
//            // Verify if new_accountID is taken already
//            $sql = "SELECT accountID FROM 1Account WHERE accountID = ?";
//
//            if($stmt = mysqli_prepare($db, $sql))
//            {
//                // Bind variables to the prepared statement as parameters
//                mysqli_stmt_bind_param($stmt, "s", $param_new_accountID);
//
//                // Set parameters
//                $param_new_accountID = trim($_POST["new_accountID"]);
//
//                // Attempt to execute the prepared statement
//                if(mysqli_stmt_execute($stmt))
//                {
//                    mysqli_stmt_store_result($stmt);
//                    //Username taken
//                    if(mysqli_stmt_num_rows($stmt) == 1)
//                    {
//                        $new_accountID_err = "This ID is already taken.";
//                    }
//                    //Update the username
//                    else
//                    {
//                        $sql = "UPDATE 1Account SET accountID = ? WHERE accountID = ?";
//                        if($stmt = mysqli_prepare($db, $sql))
//                        {
//                            // Bind variables to the prepared statement as parameters
//                            mysqli_stmt_bind_param($stmt, "ss", $param_new_accountID,$param_old_accountID);
//
//                            // Set parameters
//                            $param_new_accountID = trim($_POST["new_accountID"]);
//                            $param_old_accountID = trim($_POST["old_accountID"]);
//
//                            // Attempt to execute the prepared statement
//                            if(mysqli_stmt_execute($stmt))
//                            {
//                                $update_result = "Your ID has been successfully changed!";
//                            }
//                            else
//                            {
//                                $old_accountID_err="Please verify your information";
//                            }
//                        }
//                    }
//                }
//                else
//                {
//                    echo "Oops! Something went wrong. Please try again later.";
//                }
//                // Close statement
//                mysqli_stmt_close($stmt);
//            }
//        }
//    }
//
//    // Password modification
//    if(isset($_POST['old_password']))
//    {
//        if(empty(trim($_POST["new_password"])))
//        {
//            $new_password_err = "Please enter a new password.";
//        }
//        else
//        {
//            //Update the password
//            $sql = "UPDATE 1Account SET password = ? WHERE accountID = ? AND password = ?";
//            if($stmt = mysqli_prepare($db, $sql))
//            {
//                // Bind variables to the prepared statement as parameters
//                mysqli_stmt_bind_param($stmt, "sss", $param_new_password, $param_accountID, $param_old_password);
//
//                // Set parameters
//                $param_new_password = trim($_POST["new_password"]);
//                $param_accountID = trim($_SESSION["accountID"]);
//                $param_old_password = trim($_POST["old_password"]);
//
//                // Attempt to execute the prepared statement
//                if(mysqli_stmt_execute($stmt))
//                {
//                    $update_result = "Your password has been successfully changed!";
//                }
//                else
//                {
//                    $old_password_err="Please verify your information";
//                }
//            }
//            else
//            {
//                echo "Oops! Something went wrong. Please try again later.";
//            }
//            // Close statement
//            mysqli_stmt_close($stmt);
//        }
//    }
//
//    // Category modification
//    if(isset($_POST['new_category']))
//    {
//        if(empty(trim($_POST["new_category"])))
//        {
//            $new_category_err = "Please select a category.";
//        }
//        elseif(!isset($_POST['category_confirm']))
//        {
//            $new_category_err="Please confirm your new category choice";
//        }
//        //If downgrades to prime, we must check there are no more than 5 jobs posted
//        elseif(trim($_POST["category_confirm"])=='prime')
//        {
//            $sql = "SELECT COUNT(*) AS total_job_posts FROM 1Job WHERE accountID = '".$_SESSION['accountID']."'";
//            $result = mysqli_query($db,$sql);
//            $row=mysqli_fetch_array($result);
//            if($row['total_job_posts']>5)
//            {
//                $new_category_err="You may not change your account to Prime as you have more than 5 job posts published.";
//            }
//        }
//
//        if(empty($new_category_err))
//        {
//            //Update the category
//            $sql = "UPDATE 1User SET premiumOpt = ? WHERE accountID = ? ";
//            if($stmt = mysqli_prepare($db, $sql))
//            {
//                // Bind variables to the prepared statement as parameters
//                mysqli_stmt_bind_param($stmt, "ss", $param_new_category, $param_accountID);
//
//                // Set parameters
//                $param_new_category = trim($_POST["new_category"]);
//                $param_accountID = trim($_SESSION["accountID"]);
//
//                // Attempt to execute the prepared statement
//                if(mysqli_stmt_execute($stmt))
//                {
//                    $update_result = "Your user category has been successfully changed!";
//                }
//                else
//                {
//                    $new_category_err="Please verify your information";
//                }
//            }
//            else
//            {
//                echo "Oops! Something went wrong. Please try again later.";
//            }
//            // Close statement
//            mysqli_stmt_close($stmt);
//        }
//    }

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

        <h3>Change Login details</h3>
        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>" style="width: 70mm">
            <label>Title</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   placeholder="No more than 20 characters"
                   value="<?php echo $title; ?>">
            <span class="help-block"><?php echo $title_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($briefDescription_err)) ? 'has-error' : ''; ?>">
            <label>Brief Description</label>
            <input type="text"
                   name="briefDescription"
                   class="form-control"
                   style="width: 180mm; "
                   placeholder="No more than 50 characters"
                   value="<?php echo $briefDescription; ?>">
            <span class="help-block"><?php echo $briefDescription_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>" style="width: 80%;">
            <label>Description</label> <br>
            <div>
                <textarea name="description"
                          cols="90"
                          rows="7"
                          placeholder="No more than 2000 characters"></textarea>
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
            <input type="number" min="1" name="amountNeeded" class="form-control" style="width: 3cm" value="<?php echo $amountNeeded; ?>">
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
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
    </form>
</div>


</div>
</BODY>
</HTML>
