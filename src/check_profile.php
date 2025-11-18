<!DOCTYPE html>
<html>
    <head>
        <title>profilo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="check_profile.css">
        <link rel="stylesheet" href="background.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

<?php
    session_start();

    $username = $_SESSION["username"];
    $email = $_SESSION["email"];
    $department = $_SESSION["department"];
    $university_year = $_SESSION["university_year"];
    $enrollment_year = $_SESSION["enrollment_year"];
    $preferred_mode = $_SESSION["preferred_mode"];
    $preferred_time = $_SESSION["preferred_time"];
    $groups = $_SESSION["groups"] ?? [];

    function print_or_default($var) {
        if (!$var) {
            echo '<label class="label">Non impostato</label>';
            return;
        }

        printf('<label class="label">%s</label>', $var);
    }
?>

    <body>
        <div id="form-container">
            <form id="form">
                <div onclick="redirect()" id="close-cross">
                    ✕
                </div>
<?php
                printf('<label id="username">%s</label>', $username);
                printf('<a href="mailto:%s" class="label" id="email">%s</a>', $email, $email);
?>

                <hr>

                <label class="label-title">Facoltà</label>
<?php
                print_or_default($department);
?>
                <br>

                <label class="label-title">Anno universitario</label>
<?php
                print_or_default($university_year);
?>
                <br>

                <label class="label-title">Anno di immatricolazione</label>
<?php
                print_or_default($enrollment_year);
?>
                <br>

                <label class="label-title">Modalità preferita</label>
<?php
                print_or_default($preferred_mode);
?>
                <br>

                <label class="label-title">Orari preferiti</label>
<?php
                print_or_default($preferred_time);
?>
                <br>

                <label class="label-title">Gruppi</label>
<?php
                    if (!$groups) {
                        echo '<label class="label">Non sei ancora in nessun gruppo</label>';
                    } else {
                        echo '<ul id="group-list">';
                        foreach ($groups as $group) {
                            printf('<li class="list-entry">%s</li>', $group);
                        }
                    }
?>
                </ul>
            </form>
        </div>

        <script>
            function redirect() {
                location.href="index.php";
            } 
        </script>
    </body>
</html>

