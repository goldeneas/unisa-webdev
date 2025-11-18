<!DOCTYPE html>
<html>
    <head>
        <title>login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="login.css">
        <link rel="stylesheet" href="background.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

<?php
if (!isset($_POST["email"]) || !isset($_POST["password"])) {
?>
    <body>
        <div id="login-form-container">
            <form id="login-form" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <h1 id="login-title">Login</h1>
                <p id="login-subtitle">Inserisci le tue credenziali</p>

                <input type="text" class="text-input" name="email" placeholder="Email" required><br>

                <div id="password-container">
                    <input type="password" id="password-input" class="text-input" name="password" placeholder="Password" minlength="6" required>
                    <br>

                    <!-- we need type=button to override type=submit -->
                    <button type="button" onclick="togglePassword()" id="toggle-password">
                        mostra
                    </button>
                </div>

                <a href="/forgot-password" id="forgot-password-href">Password dimenticata?</a>

                <div id="input-separator">
                    <button class="form-btn" type="submit">Accedi</button>

                    <div id="register-container">
                        <p style="display: inline">
                            Non hai ancora un account?
                        </p>
                        <a href="register.html">
                            Registrati
                        </a>
                    </div>
                </div>
            </form>
        </div>
<?php
} else {
    session_start();
    $_SESSION["logged_in"] = true;
?>
    <body>
        <div id="login-form-container">
            <form id="login-form">
                <div>
                    <h1 style="margin-bottom: 0px;">Ti sei loggato!</h1>
                    <p style="margin-top: 3px;">Redirect alla pagina principale...</p>
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
