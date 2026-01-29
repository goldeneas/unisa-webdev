<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="login.css">
        <link rel="stylesheet" href="background.css">
        <link rel="stylesheet" href="centered_banner.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
         <script src="script_form.js" defer></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
      
       if (typeof initLoginValidation === "function") {
    initLoginValidation(); 
}
    });
</script>
    </head>

<?php
require_once "scripts/db_connection.php";
require_once "scripts/db_users.php";

require_once "centered_banner.php";
require_once "navbar.php";

if (!isset($_POST["email"]) || !isset($_POST["password"])) {
?>
    <body>
        <main id="login-form-container">
            <form id="login-form" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <h1 id="login-title">Login</h1>
                <p id="login-subtitle">Inserisci le tue credenziali</p>

                <label>Email</label>
                <input type="email" class="text-input" name="email" placeholder="Email"><br>

                <section id="password-container">
                    <label>Password</label>
                    <input type="password" id="password-input" class="text-input" name="password" placeholder="Password" minlength="6">
                    <br>

                    <button type="button" onclick="togglePassword()" id="toggle-password">
                        mostra
                    </button>
                </section>

              

                <section id="input-separator">
                    <button class="form-btn" type="submit">Accedi</button>

                    <nav id="register-container">
                        <p style="display: inline">
                            Non hai ancora un account?
                        </p>
                        <a href="register.php">
                            Registrati
                        </a>
                    </nav>
                </section>
            </form>
        </main>
<?php
} else {

   $email = $_POST["email"];
   if (!does_user_exist($db, $email)) {
        spawn_centered_banner("Email Inesistente", "Potrai riprovare a breve");
        header("refresh:3;url=login.php" );
        return;
    }

    $password = $_POST["password"];
    if (!check_user_password($db, $email, $password)) {
        spawn_centered_banner("Password Errata", "Potrai riprovare a breve");
        header("refresh:3;url=login.php" );
        return;
    }

    $user = get_user_by_email($db, $email);

    $_SESSION["name"] = $user["name"];
    $_SESSION["surname"] = $user["surname"];
    $_SESSION["email"] = $email;
    $_SESSION["university_year"] = $user["university_year"];
    $_SESSION["department"] = $user["department"];
    $_SESSION["preferred_time"] = $user["preferred_time"];
    $_SESSION["preferred_mode"] = $user["preferred_mode"];
    $_SESSION["enrollment_year"] = $user["enrollment_year"];
    $_SESSION["logged_in"] = true;

    spawn_centered_banner("Ti sei loggato!", "Redirect alla pagina principale...");
    header("refresh:3;url=index.php" );
}
?>

        <script>
            function togglePassword() {
                const passwordElement = document.getElementById("password-input");
                const passwordToggleElement = document.getElementById("toggle-password");

                passwordElement.type = passwordElement.type === "password" ? "text" : "password";
                passwordToggleElement.textContent = passwordElement.type === "password" ? "mostra" : "nascondi";
            }
        </script>
    </body>
</html>
