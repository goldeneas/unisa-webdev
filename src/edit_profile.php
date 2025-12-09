<?php
session_start();

require_once "scripts/db_connection.php";
require_once "scripts/db_users.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $year = !empty($_POST["year"]) ? $_POST["year"] : null;
    $enrollment_year = !empty($_POST["enrollment_year"]) ? $_POST["enrollment_year"] : null;
    $faculty = !empty($_POST["faculty"]) ? $_POST["faculty"] : null;
    $mode = !empty($_POST["mode"]) ? $_POST["mode"] : null;
    $times = isset($_POST["times"]) ? implode(", ", $_POST["times"]) : "";

    $email = $_SESSION["email"];

    update_user_profile($db, $email, $year, $enrollment_year, $faculty, $times, $mode);

    $_SESSION["university_year"] = $year;
    $_SESSION["enrollment_year"] = $enrollment_year;
    $_SESSION["department"] = $faculty;
    $_SESSION["preferred_time"] = $times;
    $_SESSION["preferred_mode"] = $mode;

    $profile_modified = true; 
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Creazione Profilo - Gruppi di Studio</title>
        <link rel="stylesheet" href="edit_profile.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="script_form.js" defer></script>
    </head>

<?php
    require_once "navbar.php";
    require_once "centered_banner.php";

    if (isset($profile_modified) && $profile_modified) {
        spawn_centered_banner("Profilo modificato con successo", "Verrai reindirizzato tra pochi secondi...");
        header("refresh:3;url=check_profile.php");
        return; 
    }

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        spawn_centered_banner("Non puoi accedere", "Per farlo ti serve un account");
        header("refresh:3;url=login.php" );
        return;
    }
?>

    <body>
        <div id="card-container">
            <div class="container">
                <h1>Modifica profilo</h1>
                <p id="subtitle">Inserisci informazioni aggiuntive</p>

                <form id="profileForm" method="POST"> 
                    <label for="year">Anno universitario</label>
                    <select id="year" name="year">
                        <option value="">-- Seleziona il tuo anno --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="Fuoricorso">Fuoricorso</option>
                    </select>
                    <label for="enrollment_year">Anno di immatricolazione</label>
                    <input  type="number"   id="enrollment_year"   name="enrollment_year"   min="1968"    max="2025"   placeholder="Es. 2004"  >
                    <label for="faculty">Facoltà</label>
                    <select id="faculty" name="faculty">
                        <option value="">-- Seleziona la tua facoltà --</option>
                        <option>Economia</option>
                        <option>Giurisprudenza</option>
                        <option>Ingegneria Civile</option>
                        <option>Ingegneria Informatica</option>
                        <option>Ingegneria Meccanica</option>
                        <option>Lettere e Filosofia</option>
                        <option>Lingue e Letterature Straniere</option>
                        <option>Matematica</option>
                        <option>Medicina e Chirurgia</option>
                        <option>Scienze della Comunicazione</option>
                        <option>Scienze dell’Educazione</option>
                        <option>Scienze Motorie</option>
                        <option>Scienze Politiche</option>
                        <option>Scienze e Tecnologie Alimentari</option>
                        <option>Sociologia</option>
                        <option>Non è tra le opzioni</option>
                    </select>

                <fieldset>
                    <legend>Orari preferiti per studiare</legend>

                    <div class="time-choice">
                      <input type="checkbox" id="range1" name="times[]" value="8:30-11:30">
                      <label for="range1">8:30 - 11:30</label>
                    </div>

                    <div class="time-choice">
                      <input type="checkbox" id="range2" name="times[]" value="11:30-14:30">
                      <label for="range2">11:30 - 14:30</label>
                    </div>

                    <div class="time-choice">
                      <input type="checkbox" id="range3" name="times[]" value="14:30-18:30">
                      <label for="range3">14:30 - 18:30</label>
                    </div>
                </fieldset>

                <label for="mode">Modalità preferita</label>
                <select id="mode" name="mode">
                    <option value="">-- Seleziona la modalità --</option>
                    <option value="presenza">In presenza</option>
                    <option value="online">Online</option>
                    <option value="mista">Mista</option>
                </select>

                <button type="submit" id="next">Completa il tuo profilo</button>

            </form>

            <p class="support">
            Se hai bisogno di aiuto, contatta l’assistenza all’indirizzo: 
            <a href="mailto:assistenza@gruppistudio.it">assistenza@gruppistudio.it</a>
            </p>
        </div>
        </div>
    </body>
    </html>





