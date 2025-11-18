<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="index.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <title>Homepage</title>
    </head>

<?php
    session_start();
    $email = $_SESSION["email"];
    $logged_in = $_SESSION["logged_in"];
?>

    <body>
        <div id="navbar-container">
            <a id="logo" href="index.html">StudyGroup</a>
            <ul id="navbar">
<?php
            if (!$logged_in) {
                echo '<li><a class="navbar-entry login-btn" href="login.php">Login</a></li>';
                echo '<li><a class="navbar-entry filled-btn" href="register.html">Registrati</a></li>';
            } else {
                echo '<li><a class="navbar-entry filled-btn" href="logout.php">Logout</a></li>';
            }
?>
            </ul>
        </div>

        <div id="upper">
            <h1 id="header">Trova gruppi di studio ad Unisa</h1>
            <h3 id="subheader">Smettila di leggere chat lorem ispum gay adas perche poi quando io e allora
                e quindi no praticamente si domani. Hai capito?
            </h3>
        </div>

        <form id="search-bar" action="/groups.php">
            <input id="course-input" placeholder="Inserisci il corso per vederne i gruppi">
            <button id="search-btn" type="submit">Cerca</button>
        </form>

        <div id="create-group-container">
            <span id="create-group-span">
                Non trovi il gruppo che cerchi?
            </span>
            <a id="create-group-href" href="group_creation.html">
                Crealo ora!
            </a>
        </div>

        <hr >

        <div id="groups-container">
            <div class="group">
                <h3 class="group-header"> Nome Gruppo </h3>
                <h4 class="group-subheader"> Codice </h4>
                <h5 class="group-info">
                    Descrizione gruppo qui
                    <br>
                    Membri: 4/5
                </h5>
                <button class="show-group-btn" onclick="location.href='group_preview.html'">
                    Visualizza
                </button>
            </div>

            <div class="group">
                <h3 class="group-header"> Nome Gruppo </h3>
                <h4 class="group-subheader"> Codice </h4>
                <h5 class="group-info">
                    Descrizione gruppo qui
                    <br>
                    Membri: 4/5
                </h5>
                <button class="show-group-btn" onclick="location.href='group_preview.html'"> 
                    Visualizza
                </button>
            </div>

            <div class="group">
                <h3 class="group-header"> Nome Gruppo </h3>
                <h4 class="group-subheader"> Codice </h4>
                <h5 class="group-info">
                    Descrizione gruppo qui
                    <br>
                    Membri: 4/5
                </h5>
                <button class="show-group-btn" onclick="location.href='group_preview.html'">
                    Visualizza
                </button>
            </div>

            <div class="group">
                <h3 class="group-header"> Nome Gruppo </h3>
                <h4 class="group-subheader"> Codice </h4>
                <h5 class="group-info">
                    Descrizione gruppo qui
                    <br>
                    Membri: 4/5
                </h5>
                <button class="show-group-btn" onclick="location.href='group_preview.html'">
                    Visualizza
                </button>
            </div>

            <div class="group">
                <h3 class="group-header"> Nome Gruppo </h3>
                <h4 class="group-subheader"> Codice </h4>
                <h5 class="group-info">
                    Descrizione gruppo qui
                    <br>
                    Membri: 4/5
                </h5>

                <button class="show-group-btn" onclick="location.href='group_preview.html'">
                    Visualizza
                </button>
            </div>
        </div>

    </body>
</html>
