<?php
// ser till att filen är uppkopplad till databasen
require_once "include-files/dbConnection.php";
// ser till att det värde vi behöver är definerat 
if (isset($_GET['SearchInput'])) {
// förbereder inputen så att den kan användas i en query
    $searchWord = mysqli_real_escape_string($connection, $_GET['SearchInput']);
    // här använder vi sökningen för att ta reda på bitens namn och ID, du kan söka på både namn och id, sorterad efter längden på namnet och idet
    // https://weber.itn.liu.se/~stegu76/TNMK30-2016/lego_generalquery.php?query=SELECT+parts.Partname%2C+parts.PartID%0D%0AFROM+parts%0D%0AWHERE+Partname+LIKE+%27%25Bric%25%27+OR+PartID+LIKE+%27%25bric%25%27%0D%0AORDER+BY+LENGTH%28Partname%29+ASC%2C+PartID+ASC+LIMIT+5
    $query = "  SELECT parts.Partname, parts.PartID
                FROM parts
                WHERE Partname LIKE '%".$searchWord."%' OR PartID LIKE '%".$searchWord."%'
                ORDER BY LENGTH(Partname) ASC, PartID ASC LIMIT 5";
    // tar fram resultatet
    $result = mysqli_query($connection, $query);
    // vi går igenom alla resultat
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            // namnen på delarna sparas i en array
            $resultingPartnames[] = $row['Partname'];
        }
    } else {
        // om inga resultat hittas så skapas en tom array
        $resultingPartnames = array();
    }
    // skickar tillbaka en json enkodad array vilket autokompleten kan använda
    echo json_encode($resultingPartnames);
}
?>
