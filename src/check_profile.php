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
?>

    <body>
        <div id="form-container">
            <form id="form">
                <div onclick="redirect()" id="close-cross">
                    ✕
                </div>
<?php
                $username = $_SESSION["username"];
                $email = $_SESSION["email"];
?>

<?php
                printf('<label id="username">%s</label>', $username);
                printf('<a href="mailto:%s" class="label" id="email">%s</a>', $email, $email);
?>

                <hr>

                <label class="label-title">Facoltà</label>
                <label class="label">Ingegneria informatica</label>
                <br>

                <label class="label-title">Anno universitario</label>
                <label class="label">Terzo anno</label>
                <br>

                <label class="label-title">Anno di immatricolazione</label>
                <label class="label">2023</label>
                <br>

                <label class="label-title">Modalità preferita</label>
                <label class="label">In presenza</label>
                <br>

                <label class="label-title">Orari preferiti</label>
                <label class="label">Mattina</label>
                <br>

                <label class="label-title">Gruppi</label>
                <ul id="group-list">
                    <li class="list-entry">Gruppo Analisi I</li>
                    <li class="list-entry">Gruppo Fisica II</li>
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

