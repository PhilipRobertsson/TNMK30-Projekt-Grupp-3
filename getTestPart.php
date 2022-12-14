<?php
// inkluderar en koppling till databasen
require_once "include-files/dbConnection.php";
// ser till att 'part' är definerat
if (isset($_GET['part'])) {
    // förbereder 'part' för en sökning
    $testPart = mysqli_real_escape_string($connection, $_GET['part']);          
	// använder 'part' namn eller id för att ta reda på ID	
    // https://weber.itn.liu.se/~stegu76/TNMK30-2016/lego_generalquery.php?query=SELECT+parts.PartID%0D%0AFROM+parts%0D%0AWHERE+Partname+%3D+%27Brick+2+x+2%27+OR+PartID+%3D+%27Brick+2+x+2%27%0D%0ALIMIT+1	
    $query = "  SELECT parts.PartID
                FROM parts
                WHERE Partname = '".$testPart."' OR PartID = '".$testPart."'
                LIMIT 1";
    // tar fram resultaten
    $result = mysqli_query($connection, $query);
    // vi tar fram resultatet (det är LIMIT 1) och sparar värdet i en variabel
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $partID = $row['PartID'];
    } 
    // skickar tillbaka PartID
    echo ($partID);
}
?>
