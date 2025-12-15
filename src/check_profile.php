<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>profilo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="check_profile.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

<?php
    require_once "navbar.php";
    require_once "centered_banner.php";

   
    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        spawn_centered_banner("Non puoi accedere", "Per farlo ti serve un account");
        header("refresh:3;url=login.php" );
        return;
    }


    $name = $_SESSION["name"] ?? "";
    $surname = $_SESSION["surname"] ?? "";
    $email = $_SESSION["email"] ?? "";
    $department = $_SESSION["department"] ?? "";
    $university_year = $_SESSION["university_year"] ?? "";
    $enrollment_year = $_SESSION["enrollment_year"] ?? "";
    $preferred_mode = $_SESSION["preferred_mode"] ?? "";
    $preferred_time = $_SESSION["preferred_time"] ?? "";
    $groups = $_SESSION["groups"] ?? [];
    $latitude = $_SESSION["latitude"] ?? null;
    $longitude = $_SESSION["longitude"] ?? null;
?>
    <body>
        <main id="form-container">
            <form id="form">
<?php
                printf('<label id="username">%s %s</label>', $name, $surname);
                printf('<a href="mailto:%s" class="label" id="email">%s</a>', $email, $email);
?>

                <hr>

                <section>
                    <label class="label-title">Facoltà</label>
                    <label class="label"><?= $department ?: "Non impostato"?></label>
                </section>
                <br>

                <section>
                    <label class="label-title">Anno universitario</label>
                    <label class="label"><?= $university_year ?: "Non impostato"?></label>
                </section>
                <br>

                <section>
                    <label class="label-title">Anno di immatricolazione</label>
                    <label class="label"><?= $enrollment_year ?: "Non impostato"?></label>
                </section>
                <br>

                <section>
                    <label class="label-title">Modalità preferita</label>
                    <label class="label"><?= $preferred_mode ?: "Non impostato"?></label>
                </section>
                <br>

                <section>
                    <label class="label-title">Orari preferiti</label>
                    <label class="label"><?= $preferred_time ?: "Non impostato"?></label>
                </section>
                <br>

                <section>
                    <p class="label-title">Posizione (Latitudine / Longitudine)</p>
                    <?php if ($latitude && $longitude) { ?>
                        <label class="label"><?= sprintf("%.4f / %.4f", $latitude, $longitude) ?></label>
                    <?php } else { ?>
                        <label class="label">Non impostato</label>
                    <?php } ?>
                    <br>
                </section>

                <label class="label-title">Gruppi</label>

<?php
                if (!$groups) {
                    printf('<p class="label">Non sei ancora in nessun gruppo</p>');
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

