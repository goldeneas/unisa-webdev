<!DOCTYPE html>
<html>
    <head>
        <title>Registrazione</title>
        <link rel="stylesheet" href="register.css">
        <link rel="stylesheet" href="background.css">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

<?php
require_once "navbar.php";
require_once "scripts/db_users.php";
require_once "scripts/db_connection.php";

// Controllo se il bottone "Registrati" è stato premuto
if (!isset($_POST["registratiBtn"])) {
?>
    <body>
        <div id="card-container">
            <div class="register-card">
                <h1> Registrati</h1>
                <div id="register-subtitle"> per trovare un gruppo adatto a te! </div>
                
                <form id="Register" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">

                    <p>
                        <label for="name">Nome</label>
                        <input type="text" placeholder="Inserisci il tuo nome" id="name" name="name" required />
                    </p>

                    <p>
                        <label for="cognome">Cognome</label>
                        <input type="text" placeholder="Inserisci il tuo cognome" id="surname" name="surname" required />
                    </p>
                    
                    <p>
                        <label for="email">E-mail</label>
                        <input type="email" placeholder="Inserisci la tua e-mail" id="email" name="email" required />
                    </p>
                    
                    <p>
                        <label for="password">Password (minimo 6 caratteri)</label>
                        <input type="password" placeholder="Inserisci la tua password" id="password" name="password" minlength="6" required />

                        <button type="button" onclick="togglePassword()" id="toggle-password">
                            mostra
                        </button>
                    </p>

                    <input type="submit" name="registratiBtn" value="Registrati" class="btn">

                </form>

                <p class="login-link">
                    Già registrato? <a href="login.php">Esegui il login</a>
                </p>
            </div>
        </div>

        <script>
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

<?php
} else {
     $email = $_POST["email"];
    if (does_user_exist($db, $_POST["email"])) {
?>
        <body>
            <div id="errore-id">
                <div id="errore-id-link">
                    <h3 class="error-title">Errore</h3>
                    <p class="redirect">
                        Esiste già un account con questa email.<br>
                        Verrai reindirizzato alla registrazione...
                    </p>
                </div>
            </div>

            <script>
                setTimeout(function() {
                    window.location.href = "register.php";
                }, 3000);
            </script>
        </body>
<?php
        exit;
    }
    // (SIMULAZIONE DATABASE) 
    // TODO: Use database
    session_start(); 
    
    $_SESSION["name"] = $_POST["name"]; // Prendo il vero nome inserito!
    $_SESSION["surname"] = $_POST["surname"]; 
    $_SESSION["email"] = $_POST["email"]; //Stessa cosa qui, fico!!!
    $_SESSION["logged_in"] = true;
?>
    <body>
        <div class="register-card" style="text-align: center;">
            <form id="Register">
                <div>
                    <h1 style="margin-bottom: 0px;">Benvenuto, <?php echo htmlspecialchars($_POST["name"]); ?>!</h1>
                    <p style="margin-top: 10px;">
                        Registrazione completata con successo.
                        Redirect alla pagina principale...
                    </p>
                    
                    <script>
                        window.setTimeout(function() {
                            location.href="index.php"
                        }, 3000); //Ti fa leggere i messaggi precedenti per 3secondi
                                  //e dopo ti indirizzia alla homepage.
                    </script>
                </div>
            </form>
        </div>
    </body>
<?php
}
?>
</html>
