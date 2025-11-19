<?php

function create_group($db, $name, $owner_email) {
    $owner_id = get_user_id_by_mail($db, $owner_mail);

    $sql = "INSERT INTO groups(name, owner_id) VALUES($1, $2)";
    pg_query_params($db, $sql, array($name, $owner_id));
}

function get_user_id_by_mail($db, $email) {
    $sql = "SELECT id FROM users WHERE email = $1";
    $res = pg_query_params($db, $sql, array($email));

    if (!$res || pg_num_rows($res) <= 0) {
        echo "Utente non trovato con email: " . $email;
        return false;
    }
    
    $row = pg_fetch_assoc($res);
    return $row['id'];
}

?>
