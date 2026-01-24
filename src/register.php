<?php
session_start();

require_once "scripts/db_users.php";
require_once "scripts/db_connection.php";
require_once "navbar.php";

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location: index.php" );
    exit;
}

$name_val = "";
$surname_val = "";
$email_val = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    else {
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

    <body>
        <main id="card-container">
            <section class="register-card">
                <h1> Registrati</h1>
                <p id="register-subtitle"> per trovare un gruppo adatto a te! </p>
                
                <?php if (!empty($error_message)): ?>
                    <div class="error-box">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

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
            </section>
        </main>

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
</html>