<!DOCTYPE html>
<html>
    <head>
        <title>Profilo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="check_profile.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxTlZBo="
        crossorigin=""></script>
    </head>

<?php
    session_start();

    require_once "navbar.php";
    require_once "centered_banner.php";
    
    require_once "scripts/db_connection.php"; 
    require_once "scripts/db_users.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        spawn_centered_banner("Non puoi accedere", "Per farlo ti serve un account");
        header("refresh:3;url=login.php" );
        return;
    }

    $user_data = get_user_by_email($db, $_SESSION["email"]);

    $name = $user_data["name"] ?? "";
    $surname = $user_data["surname"] ?? "";
    $email = $user_data["email"] ?? "";
    $department = $user_data["department"] ?? "";
    $university_year = $user_data["university_year"] ?? "";
    $enrollment_year = $user_data["enrollment_year"] ?? "";
    $preferred_mode = $user_data["preferred_mode"] ?? "";
    $preferred_time = $user_data["preferred_time"] ?? "";
    
    $lat = $user_data["latitude"] ?? null;
    $lon = $user_data["longitude"] ?? null;

    $groups = $_SESSION["groups"] ?? []; 
?>

    <body>
        <main id="form-container">
            <form id="form">
<?php
                printf('<h2 id="username">%s %s</h2>', $name, $surname);
                printf('<a href="mailto:%s" class="label" id="email">%s</a>', $email, $email);
?>

                <hr>

                <dl id="profile-details">
                    <dt class="label-title">Facoltà</dt>
                    <dd class="label"><?= $department ?: "Non impostato"?></dd>

                    <dt class="label-title">Anno universitario</dt>
                    <dd class="label"><?= $university_year ?: "Non impostato"?></dd>

                    <dt class="label-title">Anno di immatricolazione</dt>
                    <dd class="label"><?= $enrollment_year ?: "Non impostato"?></dd>

                    <dt class="label-title">Modalità preferita</dt>
                    <dd class="label"><?= $preferred_mode ?: "Non impostato"?></dd>

                    <dt class="label-title">Orari preferiti</dt>
                    <dd class="label"><?= $preferred_time ?: "Non impostato"?></dd>

                    <?php if($lat && $lon): ?>
                        <dt class="label-title">Posizione</dt>
                        <dd>
                            <figure id="map" style="height: 250px; width: 100%; border-radius: 5px; margin-top: 5px; margin-bottom: 20px;"></figure>
                        </dd>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                setTimeout(function() {
                                    var map = L.map('map').setView([<?= $lat ?>, <?= $lon ?>], 13); 
                                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                    }).addTo(map);
                                    L.marker([<?= $lat ?>, <?= $lon ?>]).addTo(map)
                                        .bindPopup("Posizione salvata")
                                        .openPopup();
                                    map.invalidateSize(); 
                                }, 0);
                            });
                        </script>
                    <?php endif; ?>
                </dl>

                <label class="label-title">Gruppi</label>
<?php
                if (!$groups) {
                    printf('<label class="label">Non sei ancora in nessun gruppo</label>');
                } else {
                    echo  '<ul id="group-list">';
                    foreach ($groups as $group) {
                        printf('<li class="list-entry">%s</li>', $group);
                    }
                    echo '</ul>';
                }
?>

                <button id="edit-btn" onclick='redirect("edit_profile.php")' type="button">
                    Modifica
                </button>
            </form>
        </main>

        <script>
            function redirect(destination) {
                location.href=destination;
            } 
        </script>
    </body>
</html>

