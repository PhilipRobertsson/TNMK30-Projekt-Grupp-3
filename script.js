// Byt bakgrundsfärg
function changeTheme(C1, C2, TC, LogoImg) {
   
	// Byt och spara Color1
	var Color1 = document.getElementsByClassName("Color1");
    for(let i = 0; i < Color1.length; i++) {
		Color1[i].style.backgroundColor = C1;
    } 
    localStorage.setItem('Color1', C1);

	// Byt och spara Color2
	var Color2 = document.getElementsByClassName("Color2");
    for(let j = 0; j < Color2.length; j++) {
		Color2[j].style.backgroundColor = C2;
    } 
    localStorage.setItem('Color2', C2);

	 // Byt och spara textfärg
    document.body.style.color = TC;
    localStorage.setItem('TextColor', TC);

	//Byt och spara Logga
	var IMG = document.getElementById("logo");
	if(IMG){
		IMG.src = LogoImg;
	} 
	localStorage.setItem('Image', LogoImg);

}
// När ny sida laddas (onload) kolla om det finns ett valt tema sparat, fortsätt ha detta tema
function carryOver() {
	if (localStorage.getItem('Color1') != null) {
		var Color1 = document.getElementsByClassName("Color1");
        for(let i = 0; i < Color1.length; i++) {
			Color1[i].style.backgroundColor = localStorage.getItem('Color1');	
        } 
	}

	if (localStorage.getItem('Color2') != null) {
		var Color2 = document.getElementsByClassName("Color2");
        for(let j = 0; j < Color2.length; j++) {
			Color2[j].style.backgroundColor = localStorage.getItem('Color2');	
        } 	
	}

	if (localStorage.getItem('TextColor') != null) {
    document.body.style.color = localStorage.getItem('TextColor');	
    }
	
	if (localStorage.getItem('Image') != null) {
		var IMG = document.getElementById("logo");
		if(IMG){
			document.getElementById("logo").src = localStorage.getItem('Image');
		}
    }	
}

// här deklareras strängen och arrayn som alla parts sparas i, strängen sparar partID med ";" emellan
var searchPartIDs = "";
var arrayOfPartIDs = [];
// den här funktionen aktiveras på den första formen när du försöker lägga till en bit, 
// funktionen testar biten och lägger till den i searchPartIDs om den är giltig
function addPart(){
	// vi tar värdet från inputen
	var searchValue = $("#SearchInput").val();
	// skapar en xml request
		var xhttpTest = new XMLHttpRequest();
		// säger var requesten ska leda till och inkluderar sökvärdet
		xhttpTest.open("GET", 'getTestPart.php?part=' + searchValue, true);
		// när vi får tillbaka resultaten så ska den testas
		xhttpTest.onload = function(){	
			const testdata = xhttpTest.response;
			// om vi inte får tillbaka något så skickas en alert och biten läggs inte till
			if(testdata != null && testdata != "" && testdata != " "){
				// om biten redan är med i arrayn så lägger vi inte till biten
				if(!arrayOfPartIDs.includes(testdata)){
					// om strängen är tim så blir strängen testdata, annars så läggs testdatan till efter ett ";"
					if(searchPartIDs === ""){
						searchPartIDs = testdata;
					} else{
						searchPartIDs = searchPartIDs.concat(";", testdata);
					}
					// vi sparar stringen i en hidden input field som är redo att skickas till result.php
					document.getElementById("stringOfPartIDs").value = searchPartIDs;
					// vi kallar addPartDiv vilket lägger till delen i arrayn och skapar en div för biten	
					addPartDiv(testdata);	
				} else {
					alert("Part already selected");
				}		
			} else {
			alert("Den inskrivna legoklossen hittades inte i databasen");
			}
		};
	// här skickas requestet
	xhttpTest.send();
	// vi tömmer sök inputen
	document.getElementById("SearchInput").value = ""; 
}
// den här funktionen lägger till biten i en array och skapar en div för biten på rätt plats
function addPartDiv(newPartID){
	// biten läggs till i arrayn
    arrayOfPartIDs.push(newPartID);
    // här skapas en div vilket får ett id beroende på dess index i arrayn, diven får också en class för css
    const partDiv = document.createElement("div");
    partDiv.setAttribute("id", "partDivIndex" + arrayOfPartIDs.indexOf(newPartID));
	partDiv.setAttribute("class", "LegoLådanPartDivs");
	// om legolådan hittas så läggs den nya diven in som ett barn i den lådan
	var boxDiv = document.getElementById("LegoLådan");
	if(boxDiv){
		boxDiv.appendChild(partDiv);
		// vi laddar informationen för den här diven
		loadPartInformation(arrayOfPartIDs.indexOf(newPartID), "LegoLådan");
	// om diven inte hittas så är vi på result sidan och då lägger vi till biten i YourParts och laddar informationen
	} else {
		boxDiv = document.getElementById("YourParts");

		boxDiv.appendChild(partDiv);

		loadPartInformation(arrayOfPartIDs.indexOf(newPartID), "YourParts");
	}
}
// här laddar vi informationen beroende på vilket index diven har och var den är placerad
function loadPartInformation(index, id){
	// vi hittar legolådan eller your parts där diven finns
    const boxDiv = document.getElementById(id);
	// en prefic för bilden
    const prefix = "https://weber.itn.liu.se/~stegu/img.bricklink.com/";
	// väljer alla barn element (alla divs)
    var partDivs = boxDiv.children;
    // vi väljer rätt div beroende på index
    var partDiv = partDivs[index];
	// vi bestämmer ID för biten
	var partID = arrayOfPartIDs[index];
	// vi skapar ett nytt request för att samla all information beroende på partid
	var xhttpLoad = new XMLHttpRequest();
	xhttpLoad.open("GET", 'getPartInfo.php?part=' + partID, true);
	// när informationen laddar så tar vi resultatet			
	xhttpLoad.onload = function(){
		var searchresults = xhttpLoad.response;
		// vi delar upp resultaten från en sträng till en array av strängar		
		var searchdata = searchresults.split("{[()]}");
		// det första värdet är filnament		
		var filename = searchdata[0];
		var imgsrc;
		// om filnamenet inte är noImg så ges src värdet med hjälp av prefixen
		if(filename != "noImg"){
			imgsrc = prefix + filename;
		// om bilden inte hittas så använder vi en standard bild
		} else {
			imgsrc = "images/" + filename + ".png";
		}
		// vi skapar ett bild element som vi ger bilden för biten som placeras i bitens div, bilden får klassen partDivImg		
		const imgTagElement = document.createElement("img");
		imgTagElement.setAttribute("src", imgsrc);
		imgTagElement.setAttribute("class", "partDivImg");
		partDiv.appendChild(imgTagElement);
		// vi tar fram bitens namn från sökningen som vi lägger i bitens div via en h2 tag	
		var partname = searchdata[1];
		const partNameElement = document.createElement("h2");
		const partnameTextnode = document.createTextNode(partname);
		partNameElement.appendChild(partnameTextnode);
		partDiv.appendChild(partNameElement);
		// vi skriver ut biten id som en h2 tag i bitens div 
		const partIdElement = document.createElement("h2");
		const partIdTextnode = document.createTextNode("ID: " + partID);
		partIdElement.appendChild(partIdTextnode);
		partDiv.appendChild(partIdElement);
		// vi lägger till en bild som ska agera som en knapp via JQuery, läggs i diven
		const closeDivImgElement = document.createElement("img");
		closeDivImgElement.setAttribute("src", "images/removeButton.png");
		closeDivImgElement.setAttribute("class", "partDivClose");
		partDiv.appendChild(closeDivImgElement);
	};
	// vi skickar requestet
	xhttpLoad.send();
}
// om en bild på resultatsidan inte laddar så försöker vi fixa det
function imgError(source){
	var prefix = "https://weber.itn.liu.se/~stegu/img.bricklink.";
	// id används för att direkt bestämma vilket fil format som bilden hadde
	// om bilden har problem med sin jpg bild så byter vi den till en gif fil
	if(source.id == "srcJPG"){
		// vi ger bilden ett nytt id
		source.id = "srcGIF";
		var imgSrc = source.src;
		// specificSrc är specifik till varje bit
		var specificSrc = imgSrc.split(".")[5];
		imgSrc = prefix + specificSrc + ".gif";
		// vi uppdaterar srcen för bilden
		source.src = String(imgSrc);
		// om bilden får problem igen och inte kan ladda sin bild så använder vi en färdig bestämmd bild
	} else if(source.id == "srcGIF"){
		// vi byter src och stänger av onerror funktionen på img tagen
		source.src = "images/noImg.png";
		source.onerror = ""; 
		return true; 
	}
}

// functions inside of the (document).ready function wait for the DOM page to be ready to execute javascript functions
// vi använder (document).ready för att se till att allt i dokumentet har laddats innan de följande funktionerna aktiveras
$(document).ready(function() {
	// det här är autokomplete funktionen
    $(function() {
		// när du har skrivit en bokstav så aktiveras funktionen via jquery
		$("#SearchInput").keyup(function(){
			// vi ser till att den första karaktären i searchinput inte är ett mellanrum, det fungerade tydligen inte att använda trim funktioner
			var charArray = $("#SearchInput").val().split('');
			if(charArray[0] == " "){
				charArray.shift();
			}
			// vi sätter sedan ihop arrayn till en string och uppdaterar input tagen
			var input = charArray.join('');
			document.getElementById("SearchInput").value = input;
			// vi skapar en ny request
			var xhttpSearch = new XMLHttpRequest();
			// vi definerar vilken data som ska skickas
			xhttpSearch.open("GET", 'getSearchParts.php?SearchInput=' + $("#SearchInput").val(), true);
			// när requesten kommer tillbaka får vi tillbaka datan i json format
			xhttpSearch.onload = function(){
				const searchdata = xhttpSearch.response;
				// autocomplete funktonen ser till att om 2 bokstäver är skrivna att efter 600 milisekunder ladda in datab från requestet
				$("#SearchInput").autocomplete({
					source: JSON.parse(searchdata),
					minLength: 1,
					delay: 600
				});
			};
			// här skickas requestet
			xhttpSearch.send();
		});
    });
	// den här funktionen aktiverar om click för alla kryss bilder i legolådan och your parts (index och result sidorna)
	$('#LegoLådan, #YourParts').on('click', '.partDivClose', function(){
		// när en partDivClose bild klickas tar vi reda på vilken div bilden finns i och tar reda på dess id
		var partDiv = $(this).parent();
		var removeID = partDiv.attr('id');

		// removeID ser exempelvis ut som "partDivIndex0" for index 0
		// vi tar ut siffran och sparar den
		var removeIndex = removeID.split("x")[1];
		// vi börjar på den indexpositionen som vi fick från split och tar bort 1 element
		arrayOfPartIDs.splice(removeIndex, 1);
		// vi sätter ihop arrayn för att skapa en ny sträng som sparas i den gömda inputen
		searchPartIDs = arrayOfPartIDs.join(";");
		document.getElementById("stringOfPartIDs").value = searchPartIDs;
		// vi tar bort den div som innehåller informationen om den biten som ska bort
		partDiv.remove();
		// vi försöker hitta lego lådan, om det inte går väljer vi yourparts
		var boxDiv = document.getElementById("LegoLådan");
		if(!boxDiv){
			boxDiv = document.getElementById("YourParts");
		}
		
		// vi går igenom alla divs i legolådan eller yourparts och tömmer alla id
		for(let i = 0; i < boxDiv.children.length; i++){
			partDiv = boxDiv.children[i];
			partDiv.setAttribute("id", "");
		}
		// vi går sedan igenom alla divs igen och ger de nya id beroende på den nya arrayn
		for(let i=0; i < boxDiv.children.length; i++){
			partDiv = boxDiv.children[i];
			partDiv.setAttribute("id", "partDivIndex" + i);
		}
	});
	// den här funktionen laddar in värdena för divsen om vi är på result sidan
    $(function() {
		// kollar om vi kan hitta resultatsidan
		var boxDiv = document.getElementById("YourParts");
		if(boxDiv){
			// om vi är på rätt sida kollar vi vilka bitar som ska finnas i strängen
			searchPartIDs = document.getElementById("stringOfPartIDs").value;
			// om strängen är tom så gör vi inget annars så skapar vi en array
			if(searchPartIDs != ""){
				arrayOfPartIDs = searchPartIDs.split(";");
				// vi går igenom arrayn och skapar divs med rätt index id och class, sen laddar vi informationen
				for(let i = 0; i < arrayOfPartIDs.length; i++){
					const partDiv = document.createElement("div");
					partDiv.setAttribute("id", "partDivIndex" + i);

					partDiv.setAttribute("class", "LegoLådanPartDivs");

					boxDiv.appendChild(partDiv);

					loadPartInformation(i, "YourParts");
				}
			}
		}
	});
});  
