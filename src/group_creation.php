<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Crea il tuo gruppo!</title>
        <link rel="stylesheet" href="group_creation.css">
        <link rel="stylesheet" href="background.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

    <?php
        require_once "navbar.php";

        if (!isset($_POST["group-name"]) || !isset($_POST["course"]) || !isset($_POST["subject"]) || !isset($_POST["description"]) || !isset($_POST["group-type"])) {
    ?>
        <body>
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
            session_start();
        // TODO: Use database
    ?>
        <body>
            <div id="container">
                <form id="group-creation-form">
                    <div>
                        <h1 style="margin-bottom: 0px;">Gruppo creato correttamente!</h1>
                        <p style="margin-top: 3px;">Tra poco verrai reindirizzato alla pagina principale</p>
                        <script>
                            window.setTimeout(function() {
                                location.href="index.php"
                            }, 3000);
                        </script>
                    </div>
                </form>
            </div>
        </body>
    <?php
        }
    ?>
</html>
