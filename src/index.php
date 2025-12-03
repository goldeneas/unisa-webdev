<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="index.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="script_form.js" defer></script>
        <title>Homepage</title>
    </head>

    <body>
        <?php
            require_once "navbar.php";
        ?>

        <div id="upper">
            <h1 id="header">Trova gruppi di studio ad Unisa</h1>
            <h3 id="subheader"> StudyGroup è la piattaforma collaborativa dedicata agli studenti universitari.
                <br>
                Semplifica il tuo studio, espandi il tuo network e raggiungi i tuoi obiettivi accademici insieme agli altri.
            </h3>
        </div>

        <form id="search-bar" method="GET" action="<?php echo $_SERVER["PHP_SELF"] ?>">
            <input id="course-input" name="search" placeholder="Inserisci il corso per vederne i gruppi" minlength="3">
            <button id="search-btn" type="submit">Cerca</button>
        </form>

        <div id="create-group-container">
            <span id="create-group-span">
                Non trovi il gruppo che cerchi?
            </span>
            <a id="create-group-href" href="group_creation.php">
                Crealo ora!
            </a>
        </div>

        <footer class="main-footer">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>StudyGroup UNISA</h4>
                    <p>La piattaforma progettata per aiutare gli studenti dell'Università di Salerno a trovare e creare gruppi di studio in modo semplice e veloce.</p>
                </div>
                <div class="footer-column">
                    <h4>Gruppi</h4>
                    <a href="group_creation.php">Crea un gruppo</a>
                    <a href="#">Cerca gruppi</a>
                </div>
                <div class="footer-column">
                    <h4>Contatti</h4>
                    <p>Email: info@studygroupsunisa.it</p>
                    <p>Tel: +39 081 000 0000</p>
                </div>
            </div>
        </footer>

<?php
        if (!isset($_GET["search"])) {
            return;
        }

        require_once "scripts/db_connection.php";
        require_once "scripts/db_groups.php";
        require_once "scripts/db_users.php";

        $search = $_GET["search"];
        $groups = get_groups_starting_with($db, $search);

        echo "<hr>";

        if (!$groups) {
            echo '<p id="no-results-p">Il tuo termine di ricerca non ha prodotto risultati</p>';
        } else {
            echo "<div id='groups-container'>";

            foreach ($groups as $group) {
                $name = $group['name'];
                $code = $group['id'];
                $description = $group['description'];
                $curr_members = $group['curr_members'];
                $max_members = $group['max_members'];

                printf(
                    '<div class="group">
                        <h3 class="group-header">%s</h3>
                        <h4 class="group-subheader">%s</h4>
                        <h5 class="group-info">
                            %s<br>Membri: %s/%s
                        </h5>
                        <button class="show-group-btn" onclick="redirect(\'group_preview.php?id=%s\')">
                            Visualizza
                        </button>
                    </div>', 
                    $name, 
                    $code, 
                    $description, 
                    $curr_members, 
                    $max_members, 
                    $code
                );
            }
        }

        echo "</div>";
?>

        <script>
            function redirect(destination) {
                location.href=destination;
            } 
        </script>
    </body>
</html>
