<?php
//require 'config.php';TODO UNCOMMENT
session_start();
?>
<HTML>
<HEAD>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="functions.js"></script>
</HEAD>

<BODY>
<?php require 'user_dashboard_navbar.php' //nav bar
?>
<br>
<input type="text" id="jobID">
<button onclick="searchById()">Search job by ID </button>
<div align="center">
    <table class="blueTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Post Date</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="4">
                <div class="links"><a href="#">&laquo;</a> <a class="active" href="#">1</a> <a href="#">2</a> <a href="#">3</a> <a href="#">4</a> <a href="#">&raquo;</a></div>
            </td>
        </tr>
        </tfoot>
        <tbody id="tableBody">
        <?php
        $sql = "SELECT jobID,title,briefDescription,postDate FROM 1Job";
        $result = mysqli_query($db,$sql);
        while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
            echo "<td>".$row['jobID']."</td>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['briefDescription']."</td>";
            echo "<td>".$row['postDate']."</td>";
            echo "</tr>";
        }
        ?>

        </tr>
        </tbody>

    </table>
</div>
</BODY>
</HTML>
