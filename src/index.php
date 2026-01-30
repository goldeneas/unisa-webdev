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

        <header>
            <h1 id="header">Trova gruppi di studio ad Unisa</h1>
            <h3 id="subheader"> StudyGroup è la piattaforma collaborativa dedicata agli studenti universitari.
                <br>
                Semplifica il tuo studio, espandi il tuo network e raggiungi i tuoi obiettivi accademici insieme agli altri.
            </h3>
        </header>

        <form id="search-bar" method="GET" action="<?php echo $_SERVER["PHP_SELF"] ?>">
            <input id="course-input" name="search" placeholder="Inserisci il nome, il corso o la facoltà per vederne i gruppi" minlength="3">
            <button id="search-btn" type="submit">Cerca</button>
        </form>

        <section id="create-group-container">
            <p id="create-group-span">
                Non trovi il gruppo che cerchi?
            </p>
            <a id="create-group-href" href="group_creation.php">
                Crealo ora!
            </a>
        </section>

<?php
        if (isset($_GET["search"])) {
            require_once "scripts/db_connection.php";
            require_once "scripts/db_groups.php";
            require_once "scripts/db_users.php";

            $search = $_GET["search"];
            $groups = get_groups_starting_with($db, $search);

            echo "<hr>";

            if (!$groups) {
                echo '<p id="no-results-p">Il tuo termine di ricerca non ha prodotto risultati</p>';
            } else {
                echo "<section id='groups-container'>";

                foreach ($groups as $group) {
                    $name = $group['name'];
                    $code = $group['id'];
                    $description = $group['description'];
                    $subject = $group['subject'];
                    $course = $group['course'];
                    $curr_members = $group['curr_members'];
                    $max_members = $group['max_members'];

                    printf(
                        '<article class="group">
                            <h3 class="group-header">%s</h3>
                            <h4 class="group-info">
                                Corso: %s
                                <br>Materia: %s
                                <br>Descrizione: %s
                                <br>Membri: %s/%s
                            </h4>
                            <button class="show-group-btn" onclick="redirect(\'group_preview.php?id=%s\')">
                                Visualizza
                            </button>
                        </article>', 
                        $name,
                        $course,
                        $subject,
                        $description,
                        $curr_members,
                        $max_members,
                        $code,
                    );
                }
            }

            echo "</section>";
        }
?>

        <footer class="main-footer">
            <section class="footer-content">
                <section class="footer-column">
                    <h4>StudyGroup UNISA</h4>
                    <p>La piattaforma progettata per aiutare gli studenti dell'Università di Salerno a trovare e creare gruppi di studio in modo semplice e veloce.</p>
                </section>
                <nav class="footer-column">
                    <h4>Gruppi</h4>
                    <a href="group_creation.php">Crea un gruppo</a>
                    <a href="#">Cerca gruppi</a>
                </nav>
                <address class="footer-column">
                    <h4>Contatti</h4>
                    <p>Email: info@studygroupsunisa.it</p>
                    <p>Tel: +39 081 000 0000</p>
                </address>
            </section>
        </footer>

        <script>
            function redirect(destination) {
                location.href=destination;
            } 
        </script>
    </body>
</html>

