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
<button onclick="searchById()">Search job by ID </button>
<input type="text" id="jobID">
<br>
<div align="center">
    <table class="blueTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
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
        <tr>
            <td>0</td>
            <td>Software Engineer</td>
            <td>Software</td>
            <td>01-01-2020</td>
        </tr>
        <tr>
            <td>1</td>
            <td>Part-time Dictator</td>
            <td>Politics</td>
            <td>01-01-2020</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Cat Wrestler</td>
            <td>Home</td>
            <td>01-01-2020</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Couch Tester</td>
            <td>Home</td>
            <td>01-01-2020</td>
        </tr>
        <?php
        //TODO UNCOMMENT
        /*
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
        */
        ?>

        </tbody>

    </table>
</div>
</BODY>
</HTML>
