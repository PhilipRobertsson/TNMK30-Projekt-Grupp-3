<?php
// inkluderar en fil som kopplar upp till databasen
require_once "include-files/dbConnection.php";
// kollar om 'part' är definerat
if (isset($_GET['part'])) {
	// förbereder variabeln 'part' för att användas i queryn
    $partID = mysqli_real_escape_string($connection, $_GET['part']);
	
    // https://weber.itn.liu.se/~stegu76/TNMK30-2016/lego_generalquery.php?query=SELECT+parts.Partname%2C+images.*%0D%0AFROM+parts%2C+images%0D%0AWHERE+PartID+%3D+%273001%27+AND+parts.PartID+%3D+images.ItemID%0D%0AORDER+BY+%28has_gif+%2B+has_jpg%29+DESC%2C+ColorID+ASC+LIMIT+1
    $partInfoQuery = "  SELECT parts.Partname, images.*
                        FROM parts, images
                        WHERE PartID = '". $partID ."' AND parts.PartID = images.ItemID
                        ORDER BY (has_gif + has_jpg) DESC, ColorID ASC LIMIT 1";
	// tar fram resultatet från queryn
    $partInfoResult = mysqli_query($connection, $partInfoQuery);
    // kollar om resultatet innehåller information, då jag använder LIMIT 1 används inte while
    if (mysqli_num_rows($partInfoResult) > 0) {
		// då jag använder LIMIT 1 används inte while, vi tar 1 rad
        $row = mysqli_fetch_array($partInfoResult);
        // här tar vi ut variablerna som vi vill ha
        $partname = $row['Partname'];
		
		$imgFolder = $row['ItemTypeID'];
        $imgColor = $row['ColorID'];
		// vi kollar om biten har en jpg bild
        if($row['has_jpg']) {
			$filename = "$imgFolder/$imgColor/$partID.jpg";
            // om inte så kollar vi om biten har en gif bild
		} else if($row['has_gif']){
			$filename = "$imgFolder/$imgColor/$partID.gif";
            //$filename = $imgFolder."/".$imgColor."/".$partID.".gif";
		} else{
			// annars så ger vi variabeln värdet noImg som vi använder för att ge biten en specifik bild
			$filename = "noImg";
		}
        
    } else {
		// om bitens partID och ItemID inte är samma så används den här queryn istället, då den andra queryn inte ger några resultat
		$partNameQuery = "  SELECT parts.Partname
							FROM parts
							WHERE PartID = '". $partID ."'
							LIMIT 1";
	// vi tar fram resultatet från queryn
		$partNameResult = mysqli_query($connection, $partNameQuery);
		// här bästämmer vi partname och noImg
		if (mysqli_num_rows($partNameResult) > 0) {
			$row2 = mysqli_fetch_array($partNameResult);
        
			$partname = $row2['Partname'];

			$filename = "noImg";

		}
	}
	// här sätter vi ihop informationen som en sträng vilket sätts ihopp med en serie av symboler vilket används för att splitta strängen sen
    $results = $filename . "{[()]}" . $partname;
	// skickar tillbaka strängen
	echo $results;
	
}
?>
