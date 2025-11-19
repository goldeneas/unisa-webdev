<link rel="stylesheet" href="navbar.css">

<?php
    session_start();
?>

<div id="navbar-container">
    <a id="logo" href="index.html">StudyGroup</a>
        <ul id="navbar">
<?php
        if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            </li>
<?php
        }
?>
    </ul>
</div>
