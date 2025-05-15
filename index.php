<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TL reizen</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <main>
        <section class="zoeken">
            <div class="zoekbalk-container">
                <div class="zoekbalk">
                    <form class="zoekformulier">
                        <div class="reisopties">
                            <label><input type="radio" name="reis" value="retour"> Retour</label>
                            <label><input type="radio" name="reis" value="enkele"> Enkele reis</label>
                        </div>
                        <div class="velden">
                            <div class="linkerveld">
                                <input type="text" class="input-field" placeholder="Vanaf">
                                <input type="text" placeholder="Naar">
                            </div>
                            <div class="middenveld">
                                <select>
                                    <option>1 Volwassene</option>
                                </select>
                            </div>
                            <div class="rechterveld">
                                <input type="date" placeholder="Vertrek op">
                                <input type="date" placeholder="Retour op">
                            </div>
                        </div>
                        <div class="zoekknop">
                            <button type="submit">Zoeken</button>
                        </div>
                    </form>
                </div>
            </div>
            <img src="images/banner.jpg" alt="banner">
        </section>

        <section class="bestemmingen">
            <h1>Populaire bestemmingen</h1>
            <div class="bestemmingen-flex">
                <?php
                for ($i = 1; $i <= 4; $i++) { ?>
                    <div class="bestemmingen-container">
                        <div class="bestemming">
                            <div class="banner">
                                <img src="images/bestemming.png" alt="bestemming1">
                            </div>
                            <h2>Locatie</h2>
                            <b>titel</b>
                            <div class="bestemming-info">
                                <img src="images/luchthaven.png" alt="luchthaven">
                                <p>luchthaven</p>
                            </div>
                            <p class="prijs">Vanaf <span>€49</span></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="bestemmingen-flex">
                <?php
                for ($i = 1; $i <= 4; $i++) { ?>
                    <div class="bestemmingen-container">
                        <div class="bestemming">
                            <div class="banner">
                                <img src="images/bestemming.png" alt="bestemming1">
                            </div>
                            <h2>Locatie</h2>
                            <b>titel</b>
                            <div class="bestemming-info">
                                <img src="images/luchthaven.png" alt="luchthaven">
                                <p>luchthaven</p>
                            </div>
                            <p class="prijs">Vanaf <span>€49</span></p>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="disclamer">
                <div class="i">
                    <b>i</b>
                </div>
                <p>
                    Alle getoonde prijzen zijn vanaf-prijzen op basis van een enkele reis. De prijzen kunnen wijzigen en zijn afhankelijk van de beschikbaarheid van het tarief.
                    Belastingen en toeslagen zijn inbegrepen, maar er kan wel een toeslag voor betaling in rekening worden gebracht. Je ziet de definitieve ticketprijs als je de
                    betaling bent gestart.
                </p>
            </div>
        </section>

        <section class="voorbereiding">
            <h1>Bereid je reis voor</h1>
            <div class="bereid">
                <div class="bereid-container">
                    <div class="foto"><img src="images/flight.png" alt="flight"></div>
                    <b>Online inchecken</b>
                    <p>Check online in voor je vlucht</p>
                </div>
                <div class="bereid-container">
                    <div class="foto"><img src="images/schedule.png" alt="schedule"></div>
                    <b>Beheer je boeking</b>
                    <p>Regel zelf alles voor je reis</p>
                </div>
                <div class="bereid-container">
                    <div class="foto"><img src="images/bad.png" alt="bed"></div>
                    <b>Hotels</b>
                    <p>Boek je verblijf op booking.com</p>
                </div>
                <div class="bereid-container">
                    <div class="foto"><img src="images/car.png" alt="car"></div>
                    <b>Huurauto</b>
                    <p>Huur gemakkelijk een auto</p>
                </div>
            </div>
        </section>

        <div class="line"></div>

        <section class="reclame">
            <div class="linkerkant">
                <h3>TL reizen app</h3>
                <img src="images/appstoreplaystore.png" alt="appstore">
            </div>
            <div class="rechterkant">
                <h3>Blijf op de hoogte</h3>
                <p>volg ons op social media</p>
            </div>
        </section>

    </main>
</body>

</html>