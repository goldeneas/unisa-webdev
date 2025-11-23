<?php
    session_start();

    require_once "navbar.php";
    require_once "centered_banner.php";
 
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Crea il tuo gruppo!</title>
        <link rel="stylesheet" href="group_creation.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

        <body>
  <?php
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
         spawn_centered_banner("Non puoi accedere", "Per farlo ti serve un account");
         header("refresh:3;url=login.php" );
        return;
    }

    if (!isset($_POST["group-name"]) || !isset($_POST["course"]) || !isset($_POST["subject"]) || !isset($_POST["description"]) || !isset($_POST["group-type"])) {
    ?>
            <div id="container">
                <form id="group-creation-form" method="post">
                    <h1 id="title">Crea il tuo gruppo di studio</h1>
                    <p id="subtitle">Inserisci le informazioni del tuo gruppo</p>

                    <p>
                        <label for="group-name">Nome del gruppo</label>
                    
                        <input type="text" placeholder="Inserisci il nome del tuo gruppo" id="group-name" name="group-name" required />
                    </p>

                    <p>
                        <label for="course">Corso di studi</label>

                        <input type="text" placeholder="Inserisci il corso di studi" id="course" name="course" required />
                    </p>

                    <p>
                        <label for="subject">Materia di studio</label>

                        <input type="text" placeholder="Inserisci la materia di studio" id="subject" name="subject" required />
                    </p>

                    <p>
                        <label for="description">Descrizione del gruppo</label>

                        <textarea placeholder="Inserisci una breve descrizione del tuo gruppo" id="description" name="description" required></textarea>
                    </p>

                    <p>
                        <label for="group-type">Visibilità del gruppo</label>

                        <div class="radio-option">
                            <input type="radio" id="public" name="group-type" value="public" checked>
                            <label for="public">Pubblico</label>
                        </div>

                        <div class="radio-option">
                            <input type="radio" id="private" name="group-type" value="private">
                            <label for="private">Privato</label>
                        </div>
                    </p>

                    <button id="create-btn" type="submit">Crea il gruppo</button>
                </form>

            </div>

        </body>
<?php
        } else {
            spawn_centered_banner("Gruppo Creato!", "Redirect in corso...");
            header("refresh:3;url=index.php" );
        }
?>
</html>
