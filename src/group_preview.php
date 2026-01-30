<?php
session_start();

require_once "scripts/db_connection.php";
require_once "scripts/db_groups.php";
require_once "scripts/db_users.php"; 
require_once "centered_banner.php";

// --- 1. CONTROLLO INIZIALE: ID PRESENTE? ---
if (!isset($_GET["id"]) || !$_GET["id"]) {
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
    <body>
        <?php
        require_once "navbar.php";
        spawn_centered_banner("Link non valido", "Non è stato specificato nessun gruppo.");
        ?>
    </body>
    </html>
    <?php
    exit;
}

$group_id = $_GET["id"];

// --- 1.5 DATI UTENTE ---
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
    <body>
        <?php
        require_once "navbar.php";
        spawn_centered_banner("Gruppo non trovato", "Il gruppo che hai selezionato non esiste!");
        ?>
    </body>
    </html>
    <?php
    exit;
}

// --- 2. LOGICA ELIMINAZIONE (POST - ADMIN) ---
if (isset($_POST["delete_group_btn"])) {
    if ($is_logged_in) {
        $temp_group = get_group_with_id($db, $group_id);
        $current_user_id = get_user_id_by_email($db, $_SESSION["email"]); // Aggiorno ID per sicurezza

        if ($temp_group && $temp_group["owner_id"] == $current_user_id) {
            if(delete_group($db, $group_id)) {
                header("refresh:3;url=index.php");
                ?>
                <!DOCTYPE html>
                <html lang="it">
                <head>
                    <meta charset="UTF-8">
                    <link rel="stylesheet" href="centered_banner.css">
                    <link rel="stylesheet" href="background.css">
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
                    
                    <style>
                        body, html {
                            height: 100%;
                            margin: 0;
                            font-family: "Roboto", sans-serif;
                            background-color: transparent;
                        }
                        .feedback-container {
                            height: 100%;
                            width: 100%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            padding-bottom: 10%;
                        }
                    </style>
                </head>
                <body>
                    <?php require_once "navbar.php"; ?>
                    <main class="feedback-container">
                        <?php spawn_centered_banner("Gruppo Eliminato", "Il gruppo è stato rimosso con successo."); ?>
                    </main>
                </body>
                </html>
                <?php
                exit;
            } else {
                echo "Errore durante l'eliminazione del gruppo.";
            }
        }
    }
}

// --- 3. LOGICA JOIN / LEAVE (POST) ---

// A. LOGICA PER USCIRE DAL GRUPPO 
if (isset($_POST["leave_group_btn"])) {
    if ($is_logged_in) {
        
        if (remove_user_from_group($db, $group_id, $current_user_id)) {
            
            header("Location: group_preview.php?id=" . $group_id);
            exit;
        } else {
            $error_msg = "Impossibile uscire dal gruppo.";
        }
    }
}

// B. LOGICA PER UNIRSI
if (isset($_POST["join_group_btn"])) {
    if ($is_logged_in) {
        $can_join = false; 
        $error_msg = "";

        // Caso 1: Pubblico
        if ($info_gruppo["is_public"] == 't' || $info_gruppo["is_public"] == 1) {
            $can_join = true;
        } 
        // Caso 2: Privato
        else {
            $input_pass = $_POST["group_password"] ?? "";
            if (check_group_password($db, $group_id, $input_pass)) {
                $can_join = true;
            } else {
                $error_msg = "Password errata!";
            }
        }

        if ($can_join) {
            add_user_to_group($db, $info_gruppo["name"], $current_user_email);
            header("Location: group_preview.php?id=" . $group_id);
            exit;
        } 
        elseif ($error_msg == "") {
            $error_msg = "Impossibile unirsi al gruppo.";
        }

    } else {
        header("Location: login.php");
        exit;
    }
}

// --- 4. PREPARAZIONE DATI HTML ---
$lista_utenti = get_users_in_group($db, $info_gruppo["name"]);
$nome_gruppo = $info_gruppo["name"];
$descrizione = $info_gruppo["description"];
$corso = $info_gruppo["course"];
$materia = $info_gruppo["subject"];
$max_membri = $info_gruppo["max_members"];
$membri_attuali = count($lista_utenti);
$owner_id = $info_gruppo["owner_id"];
$is_public = ($info_gruppo["is_public"] == 't' || $info_gruppo["is_public"] == 1);
$admin_name = "Sconosciuto";

$is_owner = ($is_logged_in && $current_user_id == $owner_id);
$user_already_joined = false;

$membri_display = [];
foreach ($lista_utenti as $utente) {
    if ($is_logged_in && $utente["email"] === $current_user_email) {
        $user_already_joined = true;
    }
    $is_admin = ($utente["id"] == $owner_id);
    $nome_completo = $utente["name"] . " " . $utente["surname"];
    
    if ($is_admin) { $admin_name = $nome_completo; }
    $sigla = strtoupper(substr($utente["name"], 0, 1) . substr($utente["surname"], 0, 1));
    
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
    <title>Gruppo: <?php echo $nome_gruppo; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="background.css">
    
    <link rel="stylesheet" href="group_preview.css">
</head>

<body>
    <?php require_once "navbar.php"; ?>

    <main id="main-layout" class="container">
        <article class="card">
            
            <header class="card-header">
                <h1><?php echo $nome_gruppo; ?></h1>
                <p class="subtitle">Creato da: <?php echo $admin_name; ?></p>
            </header>

            <section class="card-body">
                <div class="group-tags">
                    <span class="tag course-tag"><?php echo $corso; ?></span>
                    <span class="tag subject-tag"><?php echo $materia; ?></span>
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
                if ($is_owner) {
                    // --- CASO 1: PROPRIETARIO (Tasto Elimina) ---
                    ?>
                    <form method="post" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo gruppo? Questa azione non è reversibile.');">
                        <button type="submit" name="delete_group_btn" class="btn btn-danger">Elimina Gruppo</button>
                    </form>
                    <p style="text-align:center; font-size: 0.85em; margin-top:10px; color: #777;">Sei l'amministratore di questo gruppo.</p>
                    <?php

                } elseif (!$is_logged_in) {
                    // --- CASO 2: NON LOGGATO (Tasto Login) ---
                    ?>
                    <button onclick="window.location.href='login.php'" class="btn btn-primary" style="background-color: #f0ad4e;">Accedi per unirti</button>
                    <?php

                } elseif ($user_already_joined) {
                    // --- CASO 3: GIÀ MEMBRO (Tasto Esci) ---
                    ?>
                    <form method="post" onsubmit="return confirm('Sei sicuro di voler uscire da questo gruppo?');" style="width:100%; display: flex; justify-content: center;">
                        <button type="submit" name="leave_group_btn" class="btn btn-danger" style="background-color: #d9534f;">Esci dal gruppo</button>
                    </form>
                    <p style="text-align:center; font-size: 0.85em; margin-top:5px; color: #777;">Fai parte di questo gruppo.</p>
                    <?php

                } elseif ($membri_attuali >= $max_membri) {
                    // --- CASO 4: GRUPPO PIENO ---
                    ?>
                    <button class="btn btn-primary" disabled style="background-color: grey; cursor: not-allowed;">Gruppo Completo</button>
                    <?php

                } else {
                    // --- CASO 5: UTENTE LOGGATO PUÒ UNIRSI ---
                    if ($is_public) {
                        ?>
                        <form method="post" class="join-form">
                            <button type="submit" name="join_group_btn" class="btn btn-primary">Unisciti al gruppo</button>
                        </form>
                        <?php
                    } else {
                        ?>
                        <form method="post" class="join-form">
                            <input type="password" name="group_password" class="group-password-input" placeholder="Inserisci password gruppo" required>
                            <button type="submit" name="join_group_btn" class="btn btn-primary">Unisciti al gruppo</button>
                        </form>
                        <p class="private-group-label"><small>Questo è un gruppo privato.</small></p>
                        <?php
                    }
                }
                ?>
            </footer>
        </article>
    </main>

    <?php if (isset($error_msg) && !empty($error_msg)): ?>
        <script>
            setTimeout(function() {
                alert("<?php echo addslashes($error_msg); ?>");
            }, 50);
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>
    <?php endif; ?>

</body>
</html>