<?php
    session_start();
    session_unset();
    session_destroy();
?>

<body>
    <script>
        location.href="index.php";
    </script>
</body>
