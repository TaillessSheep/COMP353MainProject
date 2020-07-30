<HTML>
<HEAD>
   <TITLE>Date/Time Functions Demo</TITLE>
</HEAD>
<BODY>
<H1>Date/Time Functions Demo</H1>
<P>The current date and time is
<EM><?echo date("D M d, Y H:i:s", time())?></EM>
<P>Current PHP version:
<EM><?echo  phpversion()?></EM>


<?php
$servername = "oyc353.encs.concordia.ca";
$username = "oyc353_1";
$password = "NJ9VkGQc";
$dbname = "oyc353_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "<p>Connection failed.</p>";
}

$sql = "SELECT ID, name FROM test_will";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br> id: ". $row["ID"]. " - Name: ". $row["name"]."<br>";
    }
} else {
    echo "<p>0 results :(";
}

$conn->close();
?>


<p>yo! haha</p>


</BODY>
</HTML>
