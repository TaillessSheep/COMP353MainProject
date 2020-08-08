<div class="navbar" style="margin: 0">
    <a href="admin_dashboard.php"><i class="material-icons" style="color: white">home</i></a>
    <a href="admin_activity_reports.php">Activity Overview</a>
    <a href="admin_user_reports.php">User Reports</a>
    <a href="admin_account_reports.php">Outstanding Accounts Reports</a>
    <div class="dropdown">
        <button class="dropbtn">Modify Profile
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="admin_modify_profile.php">Update Profile</a>
            <a href="delete_profile.php">Delete Profile</a>
        </div>
    </div>
    <div class="topnav-right">
        <a href="logout.php">Log out</a>
        <a style="pointer-events: none;"> <?php
            date_default_timezone_set('America/Toronto');
            echo(date('d/m/Y', time()));?>
        </a>
    </div>
</div>

