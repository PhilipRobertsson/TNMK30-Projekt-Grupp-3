<?php include 'include-files/StartHead.txt';?>
    <title> Lego Lådan</title>
<?php include 'include-files/EndHead.txt';?>

<div class="center">   
    <img id="logo" src="images/LoggaDark.png" alt="Lego lådan logga"> <!--Lego-Lådan logga-->

    <div class="searchBars Color1"> <!-- Hela sökrutan -->
        <div class="searchBarRight">
            <!-- action="index.php" method="GET" -->
            <!-- https://stackoverflow.com/questions/3384960/want-html-form-submit-to-do-nothing-->
            <form onsubmit="addPart(); return false;">
                <label for="SearchInput">Sök: </label>
                <input type="text" id="SearchInput" name="SearchInput" placeholder="Enter PartID, Part Name.." autocomplete="off">
                <input type="submit" value="Lägg till"><br>
            </form>
        </div>
        
        <div class="searchBarLeft">
            <!-- override with js function, give value to hidden right before going to results-->
            <form id="searchFormWithParts" action="result.php" method="POST">
                <!-- change type to text when testing -->
                <input type="hidden" id="stringOfPartIDs" name="stringOfPartIDs">
                <input type="submit" value="Sök"><br>   
            </form>
        </div>

        <div class="dropdown"> <!-- Gömt element för som hänvisar till hjälpsidan när den hovras över-->
            <div class="QuestionMark Color2"> 
                ?
                <div class="dropdown-content Color2">
                        <p>Klicka på 'Hjälp' i meny-raden för att få mer hjälp med hur du söker!</p>
                </div>
            </div>
		</div>

    </div>
<!--"Legolådan uppdelad i tre olika divs för att göra mitten elementet responsivt beroende på antalet tillagda klossar-->
<div id="TopLådan" class = "center">

</div>
<div id="LegoLådan" class ="center">
    <!-- use xml to create and fill the divs -->
     
</div>
<div id="BottLådan" class ="center">
    
</div>

</div>

<?php include 'include-files/Footer.txt';?>
