<?php
session_start();

require_once "scripts/db_users.php";
require_once "scripts/db_connection.php";
require_once "navbar.php";

//Se l'utente è loggato lo reinderizzo alla homepage
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location: index.php" );
    exit;
}

$name_val = "";
$surname_val = "";
$email_val = "";
//Variabile per salvare tutti gli errori che trovo per poi stamparli
//alla fine dei controlli
$error_message = "";


//Questo controllo permette che il codice di salvataggio venga 
// eseguito solo quando l'utente preme effettivamente il tasto, 
// e non ogni volta che la pagina viene caricata.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Prendo i campi che l'utente ha inserito e faccio dei controlli
    $name_val = $_POST["name"] ?? "";
    $surname_val = $_POST["surname"] ?? "";
    $email_val = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($name_val) || empty($surname_val) || empty($email_val) || empty($password)) {
        $error_message = "Tutti i campi sono obbligatori.";
    } elseif (strlen($password) < 6) {
        $error_message = "La password deve contenere almeno 6 caratteri.";
    } elseif (does_user_exist($db, $email_val)) {
        $error_message = "L'email è già in uso. Scegline un'altra oppure effettua il login.";
    }
    else { //Se non ci sono errori, aggiungo l'utente al database
        if (create_user($db, $name_val, $surname_val, $email_val, $password)) {
            $_SESSION["name"] = $name_val;
            $_SESSION["surname"] = $surname_val;
            $_SESSION["email"] = $email_val;
            $_SESSION["logged_in"] = true;

            header("Location: index.php" );
            exit;
        } else {
            $error_message = "Si è verificato un errore durante la registrazione. Riprova più tardi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Registrazione</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" href="register.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="script_form.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
       
        if (typeof initRegisterValidation === "function") {
            initRegisterValidation();
        }
    });
</script>
    </head>

    <body>
        <main id="card-container">
            <section class="register-card">
                <h1> Registrati</h1>
                <p id="register-subtitle"> per trovare un gruppo adatto a te! </p>
                
                <!-- Controllo se ci sono stati errori nella registrazione
                 in caso affermativo, mostro all'utente cosa ha sbagliato -->
                <?php if (!empty($error_message)): ?>
                    <div class="error-box">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form id="Register" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

                    <p>
                        <label for="name">Nome</label>
                        <input type="text" placeholder="Inserisci il tuo nome" id="name" name="name" value="<?php echo htmlspecialchars($name_val); ?>" required/>
                    </p>

                    <p>
                        <label for="cognome">Cognome</label>
                        <input type="text" placeholder="Inserisci il tuo cognome" id="surname" name="surname" value="<?php echo htmlspecialchars($surname_val); ?>" required/>
                    </p>
                    
                    <p>
                        <label for="email">E-mail</label>
                        <input type="email" placeholder="Inserisci la tua e-mail" id="email" name="email" value="<?php echo htmlspecialchars($email_val); ?>" required/>
                    </p>
                    
                    <p>
                        <label for="password">Password (minimo 6 caratteri)</label>
                        <input type="password" placeholder="Inserisci la tua password" id="password" name="password" minlength="6" required/>

                        <button type="button" onclick="togglePassword()" id="toggle-password">
                            mostra
                        </button>
                    </p>

                    <input type="submit" name="registratiBtn" value="Registrati" class="btn">

                </form>

                <p class="login-link">
                    Già registrato? <a href="login.php">Esegui il login</a>
                </p>
            </section>
        </main>

        <script>
            //Funzione per mostrare / nascondere la password
            function togglePassword() {
                const passwordElement = document.getElementById("password");
                const passwordToggleElement = document.getElementById("toggle-password");

                if (passwordElement.type === "password") {
                    passwordElement.type = "text";
                    passwordToggleElement.textContent = "nascondi";
                } else {
                    passwordElement.type = "password";
                    passwordToggleElement.textContent = "mostra";
                }
            }
        </script>
    </body>
</html>