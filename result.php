<!-- Vi inkluderar headern, titel och början på sidan-->
<?php include 'include-files/StartHead.txt';?>
    <title> Resultat</title>
<?php include 'include-files/EndHead.txt';?>

<div class="AddParts Color1"> <!--Sökruta för att lägga till fler bitar och göra en ny sökning-->
    <div class="searchBarRight">
        <form onsubmit="addPart(); return false;"> <!-- vi kallar addpart funktionen vid submit -->
            <label for="SearchInput">Lägg till fler bitar: </label>
            <input type="text" id="SearchInput" name="SearchInput" placeholder="Enter PartID, Part Name.." autocomplete="off"> 
            <input type="submit" value="Lägg till"><br>
        </form>
    </div>
    <div class="searchBarLeft">
        <form id="searchFormWithParts" action="result.php" method="POST">
            <!-- change type to text when testing -->



<?php
// vi ser till att result.php är kopplad till databasen
require_once "include-files/dbConnection.php";
// om vi får ett värde från valueRight eller valueLeft blir de det nya viewIndex värdet annars 0
// viewindex används för att dela upp queryn för olika sidor
if(isset($_POST['valueRight'])){
	$viewIndex = $_POST['valueRight'];
} else if(isset($_POST['valueLeft'])){
	$viewIndex = $_POST['valueLeft'];
} else {
	$viewIndex = 0;
}
// om strängen av alla parts är bestämmd då sparar vi
if (isset($_POST['stringOfPartIDs'])) {
    // vi tar förbereder partID
    $partIDs = mysqli_real_escape_string($connection, $_POST['stringOfPartIDs']);
	// vi sparar skringen av parts i inputen för vidare sökning 
    echo "<input type='hidden' id='stringOfPartIDs' name='stringOfPartIDs' value='" .$partIDs."'>";
    echo "<input type='submit' value='Sök'><br>   
                </form>
            </div>";
    

?>
		<div class="dropdown"> <!-- vi skapar en dropdown som kan hjälpa med sökningen -->
            <div class="QuestionMark Color2"> 
                ?
                <div class="dropdown-content Color2">
                        <p>Klicka på 'Hjälp' i meny-raden för att få mer hjälp med hur du söker!</p>
                </div>
            </div>
		</div>
	</div>

<div id="YourPartsContainer" class="Color2"> <!--Klossarna som finns i sökningen-->
    <h1 id="partHeader"> Klossarna du baserar din sökning på! </h1>
    <div id="YourParts" class="Color1">
    </div>


</div>
<?php
	// vi gör prefixen för bilder, arrayn av bitar redo
    $prefix = "https://weber.itn.liu.se/~stegu/img.bricklink.com";
    $arrayOfPartIDs = explode(';', $partIDs);
	// vi tar ut det första värdet ur array för att lättare skriva en query
    $firstPartID = array_shift($arrayOfPartIDs);
    
    // https://www.mysqltutorial.org/mysql-subquery/
    // https://www.mysqltutorial.org/mysql-having.aspx
    // https://www.techonthenet.com/mysql/is_not_null.php
	// skapar en query med flera subquerys som kolumner
	// https://weber.itn.liu.se/~stegu76/TNMK30-2016/lego_generalquery.php?query=SELECT+inventory.SetID+as+id%2C+%0D%0A%28SELECT+SUM%28Quantity%29+FROM+inventory+WHERE+%28inventory.ItemID+%3D+%274342%27+OR+inventory.ItemID+%3D+%272456%27OR+inventory.ItemID+%3D+%272546p01%27OR+inventory.ItemID+%3D+%272550c01%27OR+inventory.ItemID+%3D+%27bear%27%29+AND+inventory.SetID+%3D+id%29+AS+totParts%2C+%0D%0A%28SELECT+SUM%28Quantity%29+FROM+inventory+WHERE+inventory.ItemID+%3D+%274342%27+AND+inventory.SetID+%3D+id%29+AS+part1%2C+%0D%0A%28SELECT+SUM%28Quantity%29+FROM+inventory+WHERE+inventory.ItemID+%3D+%272456%27+AND+inventory.SetID+%3D+id%29+AS+part2%2C+%0D%0A%28SELECT+SUM%28Quantity%29+FROM+inventory+WHERE+inventory.ItemID+%3D+%272546p01%27+AND+inventory.SetID+%3D+id%29+AS+part3%2C+%0D%0A%28SELECT+SUM%28Quantity%29+FROM+inventory+WHERE+inventory.ItemID+%3D+%272550c01%27+AND+inventory.SetID+%3D+id%29+AS+part4%2C+%0D%0A%28SELECT+SUM%28Quantity%29+FROM+inventory+WHERE+inventory.ItemID+%3D+%27bear%27+AND+inventory.SetID+%3D+id%29+AS+part5%2C+%0D%0A%28SELECT+%28%28part1+IS+NOT+NULL%29+%2B+%28part2+IS+NOT+NULL%29+%2B+%28part3+IS+NOT+NULL%29+%2B+%28part4+IS+NOT+NULL%29+%2B+%28part5+IS+NOT+NULL%29+%29%29+AS+eachPart+%0D%0AFROM+inventory+GROUP+BY+SetID+HAVING+totParts+IS+NOT+NULL+ORDER+BY+eachPart+DESC%2C+totParts+DESC%2C+LENGTH%28SetID%29+ASC%2C+SetID+ASC+LIMIT+20%2C+10%0D%0A

    $query =   "SELECT inventory.SetID as id, (SELECT SUM(Quantity) 
                FROM inventory 
                WHERE (";

    // vi loopar igenom alla bitar för att först räkna totala bitar av de vi sökte med i seten    
    $query .=  "inventory.ItemID = '". $firstPartID . "' ";
    foreach($arrayOfPartIDs as $partID){
        $query .= "OR inventory.ItemID = '" . $partID . "'";
    }

    $query .= ") AND inventory.SetID = id) AS totParts, ";
	// sen går vi igenom alla bitar och räknar hur många som finns i alla set, sparar värderna i part1 part2 part...
    $query .= "(SELECT SUM(Quantity) FROM inventory WHERE inventory.ItemID = '". $firstPartID ."' AND inventory.SetID = id) AS part1, ";

    $counter = 2;
    foreach($arrayOfPartIDs as $partID){
        $query .= "(SELECT SUM(Quantity) FROM inventory WHERE inventory.ItemID = '". $partID ."' AND inventory.SetID = id) AS part". $counter .", ";
        $counter++;
    }
	// vi skapar en subquery som räknar booleans för att se hur många unika av de valda bitarna som finns i alla sets
	$query .= "(SELECT ((part1 IS NOT NULL) ";
	$counter = 2;
	foreach($arrayOfPartIDs as $partID){
		$query .= "+ (part". $counter ." IS NOT NULL) ";
		$counter++;
	}
	
	$query .= ")) AS eachPart ";
	// vi går gruperar efter setID och ser till att den totala mängden bitar vi sökte efter är mer än 0
    $query .= "FROM inventory GROUP BY SetID HAVING totParts IS NOT NULL ORDER BY eachPart DESC, totParts DESC, LENGTH(SetID) ASC, SetID ASC LIMIT ";
	// vi delar upp queryn i next, query och prev för att kolla om knappar till höger och vänster ska laddas, via viewIndex
	$baseQuery = $query;
	$query .= $viewIndex .", 10";
	
	$newViewIndex = $viewIndex + 10;
	$nextQuery = $baseQuery . $newViewIndex .", 10";
	
	$prevViewIndex = $viewIndex - 10;
	$prevQuery = $baseQuery . $prevViewIndex .", 10";
?>



<div class="setsDivContainer Color1"> <!--Alla sets-->
	<?php 
		// vi definerar två boolean för att formatera texten lätt
		$allowPrint = true;
		$firstSet = true;
		// vi hämtar resultaten med queryn
		$result = mysqli_query($connection, $query);
		// om det finns resultat så förbereder vi en div för knappar som ska via nästa 10 eller 10 tidigare i sökningen
		if (mysqli_num_rows($result) > 0) {
			print("<div class='NextPageBar Color2'>");
			// om det finns resultat på de 10 tidigare så visas en knapp till vänster
			// en form med two hidden inputs används för att skicka informationen
			$prevResult = mysqli_query($connection, $prevQuery);
			if (mysqli_num_rows($prevResult) > 0) {
				echo "<div class='searchBarLeft'>
				<form action='result.php' method='POST'>
						
						<input type='hidden' id='valueLeft' name='valueLeft' value='". $prevViewIndex ."'>
						<input type='hidden' id='stringOfPartIDs' name='stringOfPartIDs' value='" .$partIDs. "'>
						
						<input type='submit' value='<'><br>
					</form>
				</div>";
			}
			// vi kollar om det finns resultat för nästa 10 sets gör samma sak som tidigare
			$nextResult = mysqli_query($connection, $nextQuery);
			if (mysqli_num_rows($nextResult) > 0) {
				print("<p class='pageIndex'> Sida: " . ($viewIndex/10 + 1) . " </p>");
				echo "<div class='searchBarRight'>
				<form action='result.php' method='POST'>
						
						<input type='hidden' id='valueRight' name='valueRight' value='". $newViewIndex ."'>
						<input type='hidden' id='stringOfPartIDs' name='stringOfPartIDs' value='" .$partIDs. "'>
						
						<input type='submit' value='>'><br>
					</form>
				</div>";
			}
			
			print("</div>");
		}
	// vi ser om vi får några resultat om inte så meddelar vi om det
    if (mysqli_num_rows($result) > 0) {
		// vi går igenom resultaten
        while ($row = mysqli_fetch_array($result)) {
            // tar ut each part 
			$eachPart = $row['eachPart'];
			// två booleans används för att skriva informativ text om resultaten
			if($firstSet){
				if(!($eachPart > count($arrayOfPartIDs))){
					$firstSet = false;
					$allowPrint = false;
					print("	<div>
							<h1 class='setsHeader'>Följande set innehåller ej alla sökta klossar</h1>
						</div>");
				} else {
					$firstSet = false;
					print("<h1 class='setsHeader'> Set som innehåller alla klossarna du valt för din sökning! </h1>");
				}
			} else if($allowPrint && !($eachPart > count($arrayOfPartIDs))){
				$allowPrint = false;
				print("	<div>
						<h2 class='setsHeader'>Följande set innehåller ej alla sökta klossar</h2>
						</div>");
			}
			// förbereder värden som skrivs ut
            print("\n<div class='setsDiv Color2'>");

            $setID = $row['id'];
			$quantity_of_search_parts = $row['totParts'];
			

            // med prefixen och setid så skapar vi en src, vi testar med jpg först
            print("<div class='leftSetDiv Color1'>");
             
			print("<img id='srcJPG' class='setImg' src='". $prefix ."/SL/". $setID .".jpg' alt='Ingen bild hittades' onError='imgError(this)'>");
			print ("</div>");

            // Letar bland sets för att ta reda på setname och år, tar ut den informationen
            $sql_setname_search = "	SELECT sets.SetID, sets.Setname, sets.Year
									FROM sets
									WHERE sets.SetID = '".$setID."'
									LIMIT 1";
            $specific_set_search = mysqli_query($connection, $sql_setname_search);
            $set_info = mysqli_fetch_array($specific_set_search);
            $setName = $set_info['Setname'];
            $setYear = $set_info['Year'];

			// räknar alla bitar i sets
            $sql_quantity_search = "SELECT SUM(Quantity) as sum
									FROM inventory
									WHERE inventory.SetID = '".$setID."'
									LIMIT 1";
            $quantity_search = mysqli_query($connection, $sql_quantity_search);
            $quantity_info = mysqli_fetch_array($quantity_search);
            $quantity = $quantity_info['sum'];
            

            // fyller en div med information
            print("<div class='rightSetDiv Color1'>");
                //set name
                print("<h2>". $setName ." - ". $setID ."</h2>");
                //set year
                print("<p>Lanseringsår: ". $setYear ."</p>");
                //quanitity of pieces
                print("<p>Antal klossar: ". $quantity ."</p>");
                //amount of the pieces we searched for
				print("<p>Antal klossar från sökning: ". $quantity_of_search_parts ."</p>");
				//amount of unique the pieces we searched for
                print("<p>Antal unika klossar från sökning: ". $eachPart ."</p>");
            print("</div>");

            print("</div>");
        }
    } else{
        print("<h1 class='setsHeader'>Det finns inga set baserade på dina sökta lego klossar</h1>");
    }
	//mysql_close($connection);
        // add like a form which reloads the page and changes the viewIndex by + or - 10
        //https://www.youtube.com/watch?v=ejN-oAw9vC0
}
	// här kommer knapparna igen
	if (mysqli_num_rows($result) > 0) {
		print("<div class='NextPageBar Color2'>");
			
		$prevResult = mysqli_query($connection, $prevQuery);
		if (mysqli_num_rows($prevResult) > 0) {
			echo "<div class='searchBarLeft'>
			<form action='result.php' method='POST'>
					
					<input type='hidden' id='valueLeft' name='valueLeft' value='". $prevViewIndex ."'>
					<input type='hidden' id='stringOfPartIDs' name='stringOfPartIDs' value='" .$partIDs. "'>
						
					<input type='submit' value='<'><br>
				</form>
			</div>";
		}
			
		$nextResult = mysqli_query($connection, $nextQuery);
		if (mysqli_num_rows($nextResult) > 0) {
			print("<p class='pageIndex'> Sida: " . ($viewIndex/10 + 1) . " </p>");
			echo "<div class='searchBarRight'>
			<form action='result.php' method='POST'>
					<input type='hidden' id='valueRight' name='valueRight' value='". $newViewIndex ."'>
					<input type='hidden' id='stringOfPartIDs' name='stringOfPartIDs' value='" .$partIDs. "'>
						
					<input type='submit' value='>'><br>
				</form>
			</div>";
		}
		
		print("</div>");
	}
	// vi stänger kopplingen till databasen
	mysqli_close($connection);
?>

</div>

    </body>
</html>
