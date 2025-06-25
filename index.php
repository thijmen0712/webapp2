<!DOCTYPE html>
<html lang="en">

<head>
	
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TL reizen - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function toggleRetour() {
            const retourInput = document.querySelector('input[name="retour"]');
            const retourRadio = document.querySelector('input[value="retour"]');
            retourInput.style.display = retourRadio.checked ? 'inline-block' : 'none';
            if (!retourRadio.checked) retourInput.value = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="reis"]');
            radios.forEach(radio => {
                radio.addEventListener('change', toggleRetour);
            });
            toggleRetour();
        });
    </script>



</head>

<body>
    <?php
    include 'header.php';
    include 'connect.php';
    ?>
    <main>
        <section class="zoeken">
            <div class="zoekbalk-container">
                <div class="zoekbalk">
                    <form class="zoekformulier" action="zoeken.php" method="GET">
                        <div class="reisopties">
                            <label><input type="radio" name="reis" value="retour" checked> Retour</label>
                            <label><input type="radio" name="reis" value="enkele"> Enkele reis</label>
                        </div>
                        <div class="velden">
                            <div class="linkerveld">
                                <input type="text" name="van" id="van" placeholder="Vanaf" autocomplete="off">
                                <input type="text" name="naar" id="naar" placeholder="Naar" autocomplete="off">


                            </div>
                            <div class="middenveld">
                                <input type="number" name="personen" min="1" max="10" placeholder="Aantal personen" required>
                            </div>
                            <div class="rechterveld">
								<input type="date" name="vertrek" id="vertrek">
								<input type="date" name="retour" class="retour-datum" style="display:none;" id="retour">

								<script>
								  const vandaag = new Date().toISOString().split('T')[0];
								  document.getElementById('vertrek').value = vandaag;
								  document.getElementById('retour').value = vandaag;
								</script>

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
                $sql = "SELECT id, locatie, titel, luchthaven, prijs, afbeelding FROM reizen LIMIT 8";
                $result = $conn->query($sql);

                $count = 0;
                if ($result && $result->rowCount() > 0) {
                    echo '<div class="bestemmingen-flex">';
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        if ($count > 0 && $count % 4 == 0) {
                            echo '</div><div class="bestemmingen-flex">';
                        }
                ?>
                        <div class="bestemmingen-container">
                            <a href="reis.php?id=<?php echo $row['id']; ?>" class="bestemming">
                                <div class="banner">
                                    <img src="images/<?php echo htmlspecialchars($row['afbeelding']); ?>" alt="<?php echo htmlspecialchars($row['locatie']); ?>">
                                </div>
                                <h2><?php echo htmlspecialchars($row['locatie']); ?></h2>
                                <b><?php echo htmlspecialchars($row['titel']); ?></b>
                                <div class="bestemming-info">
                                    <img src="images/luchthaven.png" alt="luchthaven">
                                    <p><?php echo htmlspecialchars($row['luchthaven']); ?></p>
                                </div>
                                <p class="prijs">Vanaf <span>€<?php echo htmlspecialchars($row['prijs']); ?></span></p>
                            </a>

                        </div>
                <?php
                        $count++;
                    }
                    echo '</div>';
                } else {
                    echo "<p>Geen reizen gevonden.</p>";
                }
                ?>
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
                <a href="inchecken.php" class="bereid-container">
                    <div class="foto"><img src="images/flight.png" alt="flight"></div>
                    <b>Online inchecken</b>
                    <p>Check online in voor je vlucht</p>
                </a>
                <a href="account.php" class="bereid-container">
                    <div class="foto"><img src="images/schedule.png" alt="schedule"></div>
                    <b>Beheer je boeking</b>
                    <p>Regel zelf alles voor je reis</p>
                </a>
                <a href="https://www.weer.nl/" class="bereid-container">
                    <div class="foto"><img src="images/cloudy.png" alt="bed"></div>
                    <b>Weer</b>
                    <p>Bekijk snel het weer voor jou vakantie</p>
                </a>
                <a href="https://www.rentalcars.com/nl/" class="bereid-container">
                    <div class="foto"><img src="images/car.png" alt="car"></div>
                    <b>Huurauto</b>
                    <p>Huur gemakkelijk een auto bij rentalcars</p>
                </a>
            </div>
        </section>

        <div class="line"></div>

        <section class="reclame">
            <div class="linkerkant">
                <h3>TL reizen app</h3>
                <?php
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $appstoreUrl = "https://apps.apple.com/";
                $playstoreUrl = "https://play.google.com/store";
                $link = $playstoreUrl;

                if (stripos($userAgent, 'iphone') !== false || stripos($userAgent, 'ipad') !== false || stripos($userAgent, 'ipod') !== false) {
                    $link = $appstoreUrl;
                }
                ?>
                <a href="<?php echo htmlspecialchars($link); ?>" target="_blank">
                    <img src="images/appstoreplaystore.png" alt="appstore">
                </a>
            </div>
            <div class="rechterkant">
                <h3>Blijf op de hoogte</h3>
                <p>volg ons op social media</p>
                <div class="socials">
                    <a href="https://x.com/TLreizen" class="social" target="_blank"><img src="images/x.png" alt="X"></a>
                    <a href="https://facebook.com/TLreizen" class="social" target="_blank"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="https://instagram.com/TLreizen" class="social" target="_blank"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="https://wa.me/?text=Volg%20TLreizen%20op%20WhatsApp" class="social" target="_blank"><img src="images/whatsapp.png" alt="WhatsApp"></a>
                    <a href="https://youtube.com/@TLreizen" class="social" target="_blank"><img src="images/youtube.png" alt="YouTube"></a>
                    <a href="https://linkedin.com/company/TLreizen" class="social" target="_blank"><img src="images/linkedin.png" alt="LinkedIn"></a>
                </div>
            </div>
        </section>

        <?php
        include("footer.php");
        ?>
    </main>
</body>

</html>