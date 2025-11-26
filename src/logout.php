<?php
    session_start();
    session_unset(); //Svuota la memoria RAM
    session_destroy(); //I dati sul server vengono cancellati
?>

<body>
    <script>
        location.href="index.php";
    </script>
</body>
