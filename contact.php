<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'header.php';
?>


<section class="spoed-contact">
    <h2>Heeft je vraag spoed?</h2>
    <p>Sta je al op het vliegveld? Of vertrekt je vlucht al over 2 uur?</p>
    <p>Dan kun je ons het beste bellen.</p>

    <label for="land-select" class="bold-label">Waar bel je vandaan?</label>
    <select id="land-select">
        <option>Nederland</option>
    </select>

    <div class="spoed-kaart">
        <h3>Nederland</h3>
        <a class="telefoon-link" >+31 (0) 12 34 56 789</a>
        <p class="tarief">(lokaal tarief)</p>
        <p>Ons Service Center helpt je graag van maandag t/m zondag van 8:00 tot 22.00 uur (Central European Time). Lokale tijden kunnen afwijken.</p>
        <p>Voor telefonische boekingen rekenen wij €10 administratiekosten.</p>

        <div class="talenbox">
            <p><b>Beschikbare talen</b></p>
            <div class="taalrij">
                <span>Engels</span><span>05:00 - 23:00</span>
            </div>
            <div class="taalrij">
                <span>Nederlands</span><span>08:00 - 22:00</span>
            </div>
        </div>
    </div>
</section>


</body>
</html>