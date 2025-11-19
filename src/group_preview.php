<?php
session_start();

// 1. CONTROLLO LOGIN
// Se l'utente non è loggato, lo rimandiamo alla pagina di login
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

// 2. SIMULAZIONE DATABASE (DATI DEL GRUPPO)
// in futuro verrà sostituita da una richiesta SQL tipo:
// $gruppo = $conn->query("SELECT * FROM gruppi WHERE id = 1");
$dati_gruppo = [
    "nome" => "Impero Romano Enjoyers",
    "admin" => "Giulio Cesare",
    "descrizione" => "Il nostro obiettivo è rifondare l'impero romano! Studiamo storia, latino e strategie militari antiche. (Daniele puzza, come sempre).",
    "partecipanti_attuali" => 3,
    "partecipanti_max" => 5,
    // Lista dei membri del gruppo (simulata)
    "membri" => [
        ["nome" => "Giulio Cesare", "ruolo" => "Admin", "sigla" => "GC", "admin" => true],
        ["nome" => "Marco Antonio", "ruolo" => "Membro", "sigla" => "MA", "admin" => false],
        ["nome" => "Cicerone", "ruolo" => "Membro", "sigla" => "CI", "admin" => false]
    ]
];

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    
    <title>Gruppo <?php echo $dati_gruppo["nome"]; ?></title>

    <link rel="stylesheet" href="group_preview.css">
    <link rel="stylesheet" href="background.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="container">
        <div class="card">
            
            <div class="card-header">
                <h1><?php echo $dati_gruppo["nome"]; ?></h1>
                
                <p class="subtitle">Creato da: <?php echo $dati_gruppo["admin"]; ?></p>
            </div>

            <div class="card-body">
                
                <h2>Descrizione del gruppo di studio</h2>
                <p class="description">
                    <?php echo nl2br($dati_gruppo["descrizione"]); ?>
                </p>

                <h2>Partecipanti (<?php echo $dati_gruppo["partecipanti_attuali"]; ?>/<?php echo $dati_gruppo["partecipanti_max"]; ?>)</h2>
                
                <div class="participants-list">
                    
                    <?php
                    // 3. CICLO FOREACH
                    // Per ogni "membro" presente nel nostro array, creiamo l'HTML corrispondente.
                    foreach ($dati_gruppo["membri"] as $membro) {
                        // Decidiamo la classe CSS per l'avatar (blu se admin, grigio se user)
                        $classe_avatar = $membro["admin"] ? "avatar avatar-admin" : "avatar";
                        $etichetta_admin = $membro["admin"] ? " (Admin)" : "";
                    ?>
                    
                        <div class="participant-item">
                            <div class="<?php echo $classe_avatar; ?>"><?php echo $membro["sigla"]; ?></div>
                            
                            <span class="participant-name">
                                <a href="check_profile.php">
                                    <?php echo $membro["nome"] . $etichetta_admin; ?>
                                </a>
                            </span>
                        </div>

                    <?php
                    } // Fine del ciclo foreach
                    ?>

                </div>
            </div>

            <div class="card-footer">
                <a href="index.php" class="btn btn-primary btn-outline" style="margin-bottom: 10px;">Torna alla home</a>
                
                <button onclick="showModal()" class="btn btn-primary">Unisciti al gruppo</button>
            </div>
        </div>
    </div>

    <div id="join-modal" class="modal">
        <div class="modal-content">
            <h3>Richiesta Inviata!</h3>
            <p>
                La tua richiesta di unirti al gruppo "<?php echo $dati_gruppo["nome"]; ?>" è stata inviata a <?php echo $dati_gruppo["admin"]; ?>.
            </p>
            <button onclick="closeModal()" class="btn btn-primary">Chiudi</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('join-modal');

        function showModal() {
            if (modal) modal.style.display = 'flex';
        }

        function closeModal() {
            if (modal) modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }
    </script>  

</body>
</html>