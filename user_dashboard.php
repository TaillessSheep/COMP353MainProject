<?php
require 'config.php'; //TODO UNCOMMENT
session_start();
?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</HEAD>

<BODY>
<?php require 'user_dashboard_navbar.php' //nav bar
?>
<div align="center">
    <h2>
        <br>
        <?php
        $accountID = $_SESSION['profileName'];
        echo 'Hello '. $accountID ."! Please select an option above";
        ?>
    </h2>

</div>
</BODY>
</HTML>