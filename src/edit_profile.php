<?php
session_start();

require_once "scripts/db_connection.php";
require_once "scripts/db_users.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $anno = !empty($_POST["anno"]) ? $_POST["anno"] : null;
    $facolta = !empty($_POST["facolta"]) ? $_POST["facolta"] : null;
    $modalita = !empty($_POST["modalita"]) ? $_POST["modalita"] : null;
    
    $orari = isset($_POST["orari"]) ? implode(", ", $_POST["orari"]) : "";

    $email = $_SESSION["email"];

    update_user_profile($db, $email, $anno, $facolta, $orari, $modalita);

    $_SESSION["university_year"] = $anno;
    $_SESSION["department"] = $facolta;
    $_SESSION["preferred_time"] = $orari;
    $_SESSION["preferred_mode"] = $modalita;

    $modifica_profilo = true; 
    
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
    
    </head>

<?php
    require_once "navbar.php";
    require_once "centered_banner.php";

    if (isset($modifica_profilo) && $modifica_profilo) {
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

                <form id="profiloForm" method="POST"> 
                    <label for="anno">Anno universitario</label>
                    <select id="anno" name="anno">
                        <option value="">-- Seleziona il tuo anno --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="fuoricorso">Fuoricorso</option>
                    </select>

                    <label for="facolta">Facoltà</label>
                    <select id="facolta" name="facolta">
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
                      <input type="checkbox" id="fascia1" name="orari[]" value="8:30-11:30">
                      <label for="fascia1">8:30 - 11:30</label>
                    </div>

                    <div class="time-choice">
                      <input type="checkbox" id="fascia2" name="orari[]" value="11:30-14:30">
                      <label for="fascia2">11:30 - 14:30</label>
                    </div>

                    <div class="time-choice">
                      <input type="checkbox" id="fascia3" name="orari[]" value="14:30-18:30">
                      <label for="fascia3">14:30 - 18:30</label>
                    </div>
                </fieldset>

                <label for="modalita">Modalità preferita</label>
                <select id="modalita" name="modalita">
                    <option value="">-- Seleziona la modalità --</option>
                    <option value="presenza">In presenza</option>
                    <option value="online">Online</option>
                    <option value="mista">Mista</option>
                </select>

                <button type="submit" id="avanti">Completa il tuo profilo</button>
                
            </form>

            <p class="assistenza">
            Se hai bisogno di aiuto, contatta l’assistenza all’indirizzo: 
            <a href="mailto:assistenza@gruppistudio.it">assistenza@gruppistudio.it</a>
            </p>
        </div>
        </div>
    </body>
</html>










