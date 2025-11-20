<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link rel="stylesheet" href="index.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <title>Homepage</title>
    </head>

    <body>
        <?php
            require_once "navbar.php";
            require_once "scripts/db_connection.php";
        ?>

        <div id="upper">
            <h1 id="header">Trova gruppi di studio ad Unisa</h1>
            <h3 id="subheader"> StudyGroup è la piattaforma collaborativa dedicata agli studenti universitari.
                <br>
                Semplifica il tuo studio, espandi il tuo network e raggiungi i tuoi obiettivi accademici insieme agli altri.
            </h3>
        </div>

        <form id="search-bar" method="GET" action="<?php echo $_SERVER["PHP_SELF"] ?>">
            <input id="course-input" name="search" placeholder="Inserisci il corso per vederne i gruppi">
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

<?php
        if (!isset($_GET["search"])) {
            return;
        }

        $search = $_GET["search"];

        // TODO: Fill with found groups from database
        $groups = array();

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

                printf('<div class="group">');
                    printf('<h3 class="group-header">%s</h3>', $name);
                    printf('<h4 class="group-subheader">%s</h4>', $code);
                    printf('<h5 class="group-info">');
                        printf('Descrizione gruppo qui<br>Membri: %s/%s', $curr_members, $max_members);
                    printf('</h5>');

                    printf('<button class="show-group-btn" onclick="redirect(\'group_preview.php?code=%s\')">', $code);
                    printf('Visualizza');
                    printf('</button>');
                printf('</div>');
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
