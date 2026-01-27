<?php
    session_start();
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
         <script src="script_form.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
       
        if (typeof initGroupCreationValidation === "function") {
            initGroupCreationValidation();
        }
    });
</script>
    </head>

        <body>
  <?php
    require_once "navbar.php";
    require_once "centered_banner.php";

    require_once "scripts/db_connection.php";
    require_once "scripts/db_groups.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        spawn_centered_banner("Non puoi accedere", "Per farlo ti serve un account");
        header("refresh:3;url=login.php" );
        return;
    }

    if (!isset($_POST["group-name"]) || !isset($_POST["facolta"]) || !isset($_POST["subject"]) || !isset($_POST["description"]) || !isset($_POST["group-type"])) {
    ?>
            <main id="container">
                <form id="group-creation-form" method="post">
                    <h1 id="title">Crea il tuo gruppo di studio</h1>
                    <p id="subtitle">Inserisci le informazioni del tuo gruppo</p>

                    <p>
                        <label for="group-name">Nome del gruppo</label>
                        <input type="text" placeholder="Inserisci il nome del tuo gruppo" id="group-name" name="group-name"/>
                    </p>

                    <p>
                        <label for="facolta">Corso di studi</label>
                        <select id="facolta" name="facolta" onchange="aggiornaMaterie()">
                            <option value="">-- Seleziona il tuo corso di studi --</option>
                            <option value="Economia">Economia</option>
                            <option value="Giurisprudenza">Giurisprudenza</option>
                            <option value="Ingegneria Informatica">Ingegneria Informatica</option>
                            <option value="Medicina">Medicina e Chirurgia</option>
                            <option value="Altro">Altro / Non in lista</option>
                        </select>
                    </p>

                    <p>
                        <label for="subject">Materia di studio</label>

                        <select id="subject" name="subject" disabled>
                            <option value="">-- Seleziona prima un corso di studi --</option>
                        </select>
                    </p>

                    <p>
                        <label for="description">Descrizione del gruppo</label>
                        <textarea placeholder="Inserisci una breve descrizione del tuo gruppo" id="description" name="description"></textarea>
                    </p>

                    <p>
                        <label for="max-members">Numero massimo di membri</label>
                        <input type="number" id="max-members" name="max-members" min="2" max="50" value="10"/>
                    </p>

                    <p>
                        <label>Visibilità del gruppo</label> <section class="radio-option">
                            <input type="radio" id="public" name="group-type" value="public" checked onchange="togglePassword()">
                            <label for="public">Pubblico</label>
                        </section>

                        <section class="radio-option">
                            <input type="radio" id="private" name="group-type" value="private" onchange="togglePassword()">
                            <label for="private">Privato</label>
                        </section>
                    </p>

                    <p id="password-container" style="display: none;">
                        <label for="group-password">Password del gruppo</label>
                        <input type="password" id="group-password" name="group-password" placeholder="Imposta una password">
                    </p>
                    <button id="create-btn" type="submit">Crea il gruppo</button>
                </form>

            </main>

            <script>
                // 1. Definiamo le liste delle materie per ogni facoltà
                const databaseMaterie = {
                    "Economia": [
                        "Analisi Matematica 1", "Economia Aziendale", "Microeconomia", 
                        "Diritto Privato", "Statistica", "Marketing"
                    ],
                    "Giurisprudenza": [
                        "Diritto Privato", "Diritto Costituzionale", "Diritto Romano", 
                        "Filosofia del Diritto", "Diritto Penale"
                    ],
                    "Ingegneria Informatica": [
                        "Analisi 1", "Fisica 1", "Fondamenti di Informatica", 
                        "Basi di Dati", "Reti di Calcolatori", "Sistemi Operativi"
                    ],
                    "Medicina": [
                        "Anatomia Umana", "Istologia", "Biochimica", 
                        "Fisiologia", "Patologia Generale"
                    ],
                    "Altro": [
                        "Altro, specifica nella descrizione"
                    ]
                };

                function aggiornaMaterie() {
                    // Prendo i riferimenti ai due menu
                    const selectFacolta = document.getElementById("facolta");
                    const selectMateria = document.getElementById("subject");
                    
                    // Quale facoltà ha scelto l'utente?
                    const facoltaScelta = selectFacolta.value;

                    // Svuoto il menu delle materie attuale
                    selectMateria.innerHTML = "";

                    // Se non ha scelto nulla (ha rimesso "-- Seleziona --")
                    if (facoltaScelta === "") {
                        selectMateria.innerHTML = '<option value="">-- Seleziona prima un corso di studi --</option>';
                        selectMateria.disabled = true; // Disabilito di nuovo
                        return;
                    }

                    // Riabilito il menu materie
                    selectMateria.disabled = false;

                    // Cerco le materie corrispondenti nella lista
                    let materieDisponibili = databaseMaterie[facoltaScelta];

                    // Se per caso ho scelto una facoltà che non ho inserito nel JS, metto un array vuoto
                    if (!materieDisponibili) {
                        materieDisponibili = ["Materia generica"];
                    }

                    // Creo le opzioni per il menu
                    materieDisponibili.forEach(function(materia) {
                        // Creo un nuovo elemento <option>
                        let nuovaOpzione = document.createElement("option");
                        nuovaOpzione.value = materia; // Cosa viene inviato al server
                        nuovaOpzione.text = materia;  // Cosa legge l'utente
                        
                        // Lo aggiungo al menu
                        selectMateria.add(nuovaOpzione);
                    });
                }

                function togglePassword() {
                    const privateRadio = document.getElementById("private");
                    const passContainer = document.getElementById("password-container");
                    const passInput = document.getElementById("group-password");

                    if (privateRadio.checked) {
                        // Se è privato: mostro il campo e lo rendo obbligatorio
                        passContainer.style.display = "block";
                        passInput.required = true;
                    } else {
                        // Se è pubblico: nascondo il campo, lo svuoto e tolgo l'obbligatorietà
                        passContainer.style.display = "none";
                        passInput.required = false;
                        passInput.value = "";
                    }
                }
            </script>

        </body>
<?php
    } else {
        $name = $_POST["group-name"];
        $course = $_POST["facolta"];
        $subject = $_POST["subject"];
        $description = $_POST["description"];
        $is_public = ($_POST["group-type"] === "public");
        $max_members = $_POST["max-members"];
        $owner_email = $_SESSION["email"];
        // Se è privato prendo la password, altrimenti null
        $password = null;
        if (!$is_public && isset($_POST["group-password"])) {
            $password = $_POST["group-password"];
        }

        if(create_group($db, $name, $course, $subject, $max_members, $description, $is_public, $owner_email,$password)){
            add_user_to_group($db, $name, $owner_email);
            spawn_centered_banner("Gruppo Creato!", "Redirect in corso...");
            header("refresh:3;url=index.php" );
        } else {
            echo "Errore creazione gruppo";
        }
    }
?>
</html>

