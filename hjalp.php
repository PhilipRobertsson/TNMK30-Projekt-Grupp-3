<?php include 'include-files/StartHead.txt';?>
    <title> Lego Lådan - Hjälp </title>
<?php include 'include-files/EndHead.txt';?>

    <div class="hjalpParent"> <!--Div för alla element på hjälpsidan-->
        <div class="allmant Color1"> <!--Div för första hjälprutan-->
            <h1 class="hjalpTitel">Allmänt om hemsidan</h1>
            <div class="hjalpText">
                <p class = "hjalpText">LEGO-LÅDAN är till för dig som vill hitta LEGO sets till specifika LEGO klossar. En förenklad instruktion till hur du använder LEGO-LÅDAN finner du längre ned på denna sida. 
                    Under fliken "Kontakta oss" hittar du kontaktinformation till oss som har byggt LEGO-LÅDAN. I menyn kan du även ändra mellan "Mörkt" och "Ljust" tema på hemsidan, "Mörkt" är valt som standrad.
                </p>        
            </div>
        </div>
        <div class="hjalpRuta Color2"> <!--Div för hjälprutan om sökfunktionen-->
            <h1 class="hjalpTitel"> Hur du använder sök funktionen</h1>
                <div class="left Color1">
                    <div class="hjalpBild">
                        <img src="images/HjalpSok1.png" alt="hjälp bild">
                    </div>
                </div>
            <div class="right Color1">
                <div class=hjalpText>
                    <p class="hjalpText">För att söka efter LEGO set går du till startsidan och skriver in den LEGO kloss du söker efter i sökrutan. Detta gör du genom att antingen skriva in det engelska namnet eller ID-nummret på klossen, till exempel "Brick 2 x 4" eller "3001". 
                        När du funnit den biten du söker efter trycker du på "Lägg till" bredvid sökrutan. Då kommer klossen läggas till i "lådan" som är placerad under sökrutan. Råkar det bli fel kloss kan du trycka på det röda krysset till höger om klossen i lådan för att ta bort den. 
                        När du har lagt till de klossar du letar efter trycker du "Sök", vilket kommer omdirigera dig till resultatsidan.
                </div>
            </div>
        </div>
        <div class="hjalpRuta Color2"> <!--Div för hjälprutan om resultatsidan-->
            <h1 class="hjalpTitel">Dina resultat</h1>
            <div class="left Color1">
                <div class="hjalpBild">
                    <img src="images/HjalpResult.png" alt="hjälp bild">
                </div>
            </div>
            <div class="right Color1">
                <div class=hjalpText>
                    <p class = "hjalpText">När du har omdirigerats till resultatsidan kommer du (om sökningen gett något resultat) få se en lista på de LEGO set som innehåller någon eller några av dina valda klossar.
                        Listan är sorterad så att det/de LEGO seten som innehåller flest antal av de klossarna du sökt på kommer presenteras först.

                        På resultatsidan kan du även fortsätta lägga till eller ta bort klossar för att fortsätta söka efter LEGO sets. Detta gör du på samma sätt som på startsidan. 
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php 
include 'include-files/Footer.txt';
?>
