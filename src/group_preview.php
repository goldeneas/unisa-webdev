<?php
session_start();

require_once "scripts/db_connection.php";
require_once "scripts/db_groups.php";
require_once "scripts/db_users.php"; 
require_once "centered_banner.php";

// --- 1. CONTROLLO INIZIALE: ID PRESENTE? ---
if (!isset($_GET["id"]) || !$_GET["id"]) {
    // Se manca l'ID, mostriamo errore e redirect
    header("refresh:3;url=index.php");
    ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="centered_banner.css">
        <link rel="stylesheet" href="background.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <?php
    require_once "navbar.php";
    spawn_centered_banner("Link non valido", "Non è stato specificato nessun gruppo.");
    exit; // Stop esecuzione
}

$group_id = $_GET["id"];

$is_logged_in = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"];
$current_user_email = $is_logged_in ? $_SESSION["email"] : "";
$current_user_id = $is_logged_in ? get_user_id_by_email($db, $current_user_email) : -1;

$info_gruppo = get_group_with_id($db, $group_id);
if (!$info_gruppo) {
    header("refresh:3;url=index.php");
    ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="centered_banner.css">
        <link rel="stylesheet" href="background.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <?php
    require_once "navbar.php";
    spawn_centered_banner("Gruppo non trovato", "Il gruppo che hai selezionato non esiste!");
    exit;
    exit;
}

// --- 2. LOGICA ELIMINAZIONE (POST) ---
// Controlliamo se è stato premuto il tasto elimina PRIMA di caricare il resto
if (isset($_POST["delete_group_btn"])) {
    
    // Sicurezza: L'utente deve essere loggato
    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        
        // Recuperiamo i dati per verificare che sia DAVVERO il proprietario
        $temp_group = get_group_with_id($db, $group_id);
        $current_user_id = get_user_id_by_email($db, $_SESSION["email"]);

        // Se il gruppo esiste e l'utente loggato è il proprietario
        if ($temp_group && $temp_group["owner_id"] == $current_user_id) {
            
            // CHIAMATA ALLA FUNZIONE CHE HAI AGGIUNTO IN DB_GROUPS.PHP
            if(delete_group($db, $group_id)) {
                // Successo
                header("refresh:3;url=index.php");
                ?>
                <!DOCTYPE html>
                <html lang="it">
                <head>
                    <link rel="stylesheet" href="centered_banner.css">
                    <link rel="stylesheet" href="background.css">
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
                </head>
                <?php
                require_once "navbar.php";
                spawn_centered_banner("Gruppo Eliminato", "Il gruppo è stato rimosso con successo.");
                exit;
            } else {
                echo "Errore durante l'eliminazione del gruppo.";
            }
        }
    }
}

// --- 3. RECUPERO DATI GRUPPO ---
$info_gruppo = get_group_with_id($db, $group_id);

// Se il gruppo non esiste (o è stato appena cancellato e ricarichi la pagina)
if (!$info_gruppo) {
    header("refresh:3;url=index.php");
    ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="centered_banner.css">
        <link rel="stylesheet" href="background.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <?php
    require_once "navbar.php";
    spawn_centered_banner("Gruppo non trovato", "Il gruppo che hai selezionato non esiste!");
    exit;
}

if (isset($_POST["join_group_btn"])) {
    if ($is_logged_in) {
        $can_join = false; 
        $error_msg = "";

        // Caso 1: Gruppo Pubblico -> Accesso libero
        if ($info_gruppo["is_public"] == 't' || $info_gruppo["is_public"] == 1) {
            $can_join = true;
        } 
        // Caso 2: Gruppo Privato -> Verifica Password
        else {
            $input_pass = $_POST["group_password"] ?? "";
            
            if (check_group_password($db, $group_id, $input_pass)) {
                $can_join = true;
            } else {
                $error_msg = "Password errata!";
            }
        }

        // Se $can_join è true, procediamo all'inserimento
        if ($can_join) {
            
            add_user_to_group($db, $info_gruppo["name"], $current_user_email);
            
            // Ricarica la pagina per mostrare che l'utente è stato effettivamente inserito
            header("Location: group_preview.php?id=" . $group_id);
            exit;
            
        } 
        // Gestione errore (mostra il messaggio solo se non si è ricaricata la pagina)
        elseif ($error_msg == "") {
            $error_msg = "Impossibile unirsi al gruppo.";
        }

    } else {
        // Se non è loggato
        header("Location: login.php");
        exit;
    }
}

// --- 4. PREPARAZIONE DATI PER HTML ---
$lista_utenti = get_users_in_group($db, $info_gruppo["name"]);
$nome_gruppo = $info_gruppo["name"];
$descrizione = $info_gruppo["description"];
$corso = $info_gruppo["course"];
$materia = $info_gruppo["subject"];
$max_membri = $info_gruppo["max_members"];
$membri_attuali = count($lista_utenti);
$owner_id = $info_gruppo["owner_id"];
$is_public = ($info_gruppo["is_public"] == 't' || $info_gruppo["is_public"] == 1);




// Sei il proprietario?
$is_owner = ($is_logged_in && $current_user_id == $owner_id);
$user_already_joined = false;

// Loop Membri per visualizzazione
$membri_display = [];
foreach ($lista_utenti as $utente) {
    if ($is_logged_in && $utente["email"] === $current_user_email) {
        $user_already_joined = true;
    }

    $is_admin = ($utente["id"] == $owner_id);
    $nome_completo = $utente["name"] . " " . $utente["surname"];
    
    // Verifico se io (utente loggato) sono in questa lista
    if ($is_logged_in && $utente["email"] === $current_user_email) {
        $user_already_joined = true;
    }

    $sigla = strtoupper(substr($utente["name"], 0, 1) . substr($utente["surname"], 0, 1));
    
    if ($is_admin) {
        $admin_name = $nome_completo;
    }

    $membri_display[] = [
        "nome" => $nome_completo,
        "sigla" => $sigla,
        "admin" => $is_admin,
        "email" => $utente["email"]
    ];
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gruppo <?php echo $nome_gruppo; ?></title>
    
    <link rel="stylesheet" href="group_preview.css">
    <link rel="stylesheet" href="background.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<?php require_once "navbar.php"; ?>

<body>
    <?php if (isset($error_msg) && !empty($error_msg)): ?>
        <script>
            // Questo codice viene generato solo se c'è un errore PHP 
            // (Pass errata)
            alert("<?php echo addslashes($error_msg); ?>");
            
        </script>
    <?php endif; ?>

    <main class="container">
        <article class="card">
            
            <header class="card-header">
                <h1><?php echo $nome_gruppo; ?></h1>
                <p class="subtitle">Creato da: <?php echo $admin_name; ?></p>
            </header>

            <section class="card-body">
                <div class="group-tags">
                    <span class="tag course-tag">
                        <?php echo $corso; ?>
                    </span>
                    <span class="tag subject-tag">
                        <?php echo $materia; ?>
                    </span>
                </div>
                <h2>Descrizione del gruppo di studio</h2>
                <p class="description">
                    <?php echo nl2br($descrizione); ?>
                </p>

                <h2>Partecipanti (<?php echo $membri_attuali; ?>/<?php echo $max_membri; ?>)</h2>
                
                <section class="participants-list">
                    <?php foreach ($membri_display as $membro) { 
                        $classe_avatar = $membro["admin"] ? "avatar avatar-admin" : "avatar";
                        $etichetta_admin = $membro["admin"] ? " (Admin)" : "";
                    ?>
                        <article class="participant-item">
                            <aside class="<?php echo $classe_avatar; ?>"><?php echo $membro["sigla"]; ?></aside>
                            <bdi class="participant-name">
                                <a href="check_profile.php?email=<?php echo $membro['email']; ?>">
                                    <?php echo $membro["nome"] . $etichetta_admin; ?>
                                </a>
                            </bdi>
                        </article>
                    <?php } ?>
                </section>
            </section>

            <footer class="card-footer">
                <?php 
                // --- BOTTONI AZIONE ---
                
                if ($is_owner) {
                    // SEI IL PROPRIETARIO -> Bottone Elimina
                    ?>
                    <form method="post" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo gruppo? Questa azione non è reversibile.');">
                        <button type="submit" name="delete_group_btn" class="btn btn-danger">
                            Elimina Gruppo
                        </button>
                    </form>
                    <p style="text-align:center; font-size: 0.85em; margin-top:10px; color: #777;">
                        Sei l'amministratore di questo gruppo.
                    </p>
                    <?php

                } elseif (!$is_logged_in) {
                    // NON LOGGATO -> Login
                    ?>
                    <button onclick="window.location.href='login.php'" class="btn btn-primary" style="background-color: #f0ad4e;">
                        Accedi per unirti
                    </button>
                    <?php

                } elseif ($user_already_joined) {
                    // GIÀ MEMBRO -> Info
                    ?>
                    <button class="btn btn-primary" disabled style="background-color: #5cb85c; cursor: default;">
                        Sei già membro
                    </button>
                    <?php

                } elseif ($membri_attuali >= $max_membri) {
                    // PIENO -> Disabilitato
                    ?>
                    <button class="btn btn-primary" disabled style="background-color: grey; cursor: not-allowed;">
                        Gruppo Completo
                    </button>
                    <?php

                } else {
                    // LOGGATO E LIBERO -> LOGICA JOIN
                    
                    if ($is_public) {
                        // --- GRUPPO PUBBLICO ---
                        ?>
                        <form method="post" class="join-form">
                            <button type="submit" name="join_group_btn" class="btn btn-primary">
                                Unisciti al gruppo
                            </button>
                        </form>
                        <?php
                    } else {
                        // --- GRUPPO PRIVATO ---
                        ?>
                        <form method="post" class="join-form">
                            <input type="password" 
                                   name="group_password" 
                                   class="group-password-input" 
                                   placeholder="Inserisci password gruppo" 
                                   required>
                            
                            <button type="submit" name="join_group_btn" class="btn btn-primary">
                                Unisciti al gruppo
                            </button>
                        </form>
                        
                        <p class="private-group-label">
                            <small>Questo è un gruppo privato.</small>
                        </p>
                        <?php
                    }
                }
                ?>
            </footer>
        </article>
    </main>

    <aside id="join-modal" class="modal">
        <section class="modal-content">
            <h3>Richiesta Inviata!</h3>
            <p>La tua richiesta di unirti al gruppo "<?php echo $nome_gruppo; ?>" è stata inviata a <?php echo $admin_name; ?>.</p>
            <button onclick="closeModal()" class="btn btn-primary">Chiudi</button>
        </section>
    </aside>

    <script>
        const modal = document.getElementById('join-modal');
        function showModal() { if (modal) modal.style.display = 'flex'; }
        function closeModal() { if (modal) modal.style.display = 'none'; }
        window.onclick = function(event) { if (event.target == modal) closeModal(); }
    </script>  
</body>
</html>
