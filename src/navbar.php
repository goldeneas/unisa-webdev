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
                <a class="navbar-avatar" href="check_profile.php">ጰ</a>
            </li>
<?php
        }
?>
    </ul>
</div>
