<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registrazione</title>
        <link rel="stylesheet" href="register.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="script_form.js" defer></script>
    </head>

<?php
    require_once "navbar.php";
    require_once "centered_banner.php";
    
    require_once "scripts/db_users.php";
    require_once "scripts/db_connection.php";

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        spawn_centered_banner("Sei gia' loggato!", "Prima effettua il logout");
        header("refresh:3;url=index.php" );
        return;
    }
    
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
                        <input type="text" placeholder="Inserisci il tuo nome" id="name" name="name" />
                    </p>

                    <p>
                        <label for="cognome">Cognome</label>
                        <input type="text" placeholder="Inserisci il tuo cognome" id="surname" name="surname" />
                    </p>
                    
                    <p>
                        <label for="email">E-mail</label>
                        <input type="email" placeholder="Inserisci la tua e-mail" id="email" name="email" />
                    </p>
                    
                    <p>
                        <label for="password">Password (minimo 6 caratteri)</label>
                        <input type="password" placeholder="Inserisci la tua password" id="password" name="password" minlength="6" />

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
        spawn_centered_banner("Email già in uso!", "Potrai riprovare a breve...");
        header("refresh:3;url=register.php");
        exit;
    }

    // (SIMULAZIONE DATABASE) 
    // TODO: Use database
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if(create_user($db, $name, $surname, $email, $password)) {
        $_SESSION["name"] = $name; // Prendo il vero nome inserito!
        $_SESSION["surname"] = $surname; 
        $_SESSION["email"] = $email; //Stessa cosa qui, fico!!!
        $_SESSION["password"] = $password;
        $_SESSION["logged_in"] = true;
        spawn_centered_banner("Registrazione completata!", "Redirect alla pagina principale...");
        header("refresh:3;url=index.php");
    }else {
        spawn_centered_banner("Errore nella registrazione!", "Riprova tra qualche istante...");
        header("refresh:3;url=register.php");
    }
}
?>
</html>
