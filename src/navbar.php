<link rel="stylesheet" href="navbar.css">

<div id="navbar-container">
    <a id="logo" href="index.php">StudyGroup</a>
        <ul id="navbar">
<?php
        if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        //Controlla se la variabile esiste || Controlla se il valore è falso
        // Se non esiste (non è ancora stata settata) o è falso (l'utente non
        // si è loggato) viene mostrato l'else.
?>
            <li class="navbar-li">
                <a class="navbar-entry login-btn" href="login.php">Login</a>
            </li>

            <li class="navbar-li">
                <a class="navbar-entry filled-btn" href="register.php">Registrati</a>
            </li>
<?php
        } else {
?>
            <li class="navbar-li">
                <a class="navbar-entry filled-btn" href="logout.php">Logout</a>
            </li>
            <li class="navbar-li">
                <!-- icona per il profilo in svg -->
                <a class="navbar-avatar" href="check_profile.php">
                    <img src="profile_image.svg" />
                </a>
            </li>
<?php
        }
?>
    </ul>
</div>
