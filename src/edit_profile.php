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
    
    $lat = !empty($_POST["latitude"]) ? $_POST["latitude"] : null;
    $lon = !empty($_POST["longitude"]) ? $_POST["longitude"] : null;

    $email = $_SESSION["email"];

    update_user_profile($db, $email, $year, $enrollment_year, $faculty, $times, $mode, $lat, $lon);

    $_SESSION["university_year"] = $year;
    $_SESSION["enrollment_year"] = $enrollment_year;
    $_SESSION["department"] = $faculty;
    $_SESSION["preferred_time"] = $times;
    $_SESSION["preferred_mode"] = $mode;
    
    if($lat && $lon) {
        $_SESSION["latitude"] = $lat;
        $_SESSION["longitude"] = $lon;
    }

    $profile_modified = true; 
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Modifica Profilo</title>
        <link rel="stylesheet" href="edit_profile.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="script_form.js" defer></script>
        
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
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
        <main id="card-container">
            <section class="container">
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
                    <input type="number" id="enrollment_year" name="enrollment_year" min="1968" max="2025" placeholder="Es. 2004">
                    
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
                        <section class="time-choice">
                          <input type="checkbox" id="range1" name="times[]" value="8:30-11:30">
                          <label for="range1">8:30 - 11:30</label>
                        </section>
                        <section class="time-choice">
                          <input type="checkbox" id="range2" name="times[]" value="11:30-14:30">
                          <label for="range2">11:30 - 14:30</label>
                        </section>
                        <section class="time-choice">
                          <input type="checkbox" id="range3" name="times[]" value="14:30-18:30">
                          <label for="range3">14:30 - 18:30</label>
                        </section>
                    </fieldset>

                    <label for="mode">Modalità preferita</label>
                    <select id="mode" name="mode">
                        <option value="">-- Seleziona la modalità --</option>
                        <option value="presenza">In presenza</option>
                        <option value="online">Online</option>
                        <option value="mista">Mista</option>
                    </select>

                    <label>Posizione</label>
                    <button type="button" id="geo-btn">
                        Rivela la mia posizione 📍
                    </button>
                    
                    <figure id="map" style="display: none;"></figure>
                    
                    <input type="hidden" id="lat-input" name="latitude">
                    <input type="hidden" id="lon-input" name="longitude">

                    <button type="submit" id="next">Completa il tuo profilo</button>
                </form>

                <p class="support">
                Se hai bisogno di aiuto, contatta l’assistenza all’indirizzo: 
                <a href="mailto:assistenza@gruppistudio.it">assistenza@gruppistudio.it</a>
                </p>
            </section>
        </main>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const geoBtn = document.getElementById("geo-btn");
                const mapDiv = document.getElementById("map"); 
                const latInput = document.getElementById("lat-input");
                const lonInput = document.getElementById("lon-input");
                let map, marker;

                geoBtn.addEventListener("click", function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition, showError);
                    } else {
                        alert("La geolocalizzazione non è supportata dal tuo browser.");
                    }
                });

                function showPosition(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    latInput.value = lat;
                    lonInput.value = lon;

                    mapDiv.style.display = "block";

                    if (!map) {
                        map = L.map('map').setView([lat, lon], 13);
                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);
                    } else {
                        map.setView([lat, lon], 13);
                    }

                    if (marker) {
                        marker.setLatLng([lat, lon]);
                    } else {
                        marker = L.marker([lat, lon]).addTo(map)
                            .bindPopup("Sei qui!")
                            .openPopup();
                    }
                    
                    setTimeout(() => { map.invalidateSize(); }, 100);
                }

                function showError(error) {
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Utente ha negato la richiesta di geolocalizzazione.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Le informazioni sulla posizione non sono disponibili.");
                            break;
                        case error.TIMEOUT:
                            alert("La richiesta per ottenere la posizione è scaduta.");
                            break;
                        case error.UNKNOWN_ERROR:
                            alert("Si è verificato un errore sconosciuto.");
                            break;
                    }
                }
            });
        </script>
    </body>
</html>



