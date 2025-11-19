<?php

function create_user($name, $surname, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(name, surname, email, password_hash) VALUES($1, $2, $3, $4)";

    pg_query_params($db, $sql, array($name, $surname, $email, $hash));
}

function does_user_exist($db, $email) {
    $sql = "SELECT 1 FROM users WHERE email = $1";
    $res = pg_query_params($db, $sql, array($email));
    if (!$res) { return false; }

    return pg_num_rows($res) > 0;
}

function check_user_password($db, $email, $password) {
    $sql = "SELECT password_hash FROM users WHERE email = $1";
    $res = pg_query_params($db, $sql, array($email));

    $stored_hash = fetch($res);
    if (!stored_hash) { return false; }

    $curr_hash = password_hash($password, PASSWORD_DEFAULT);
    return $stored_hash === $curr_hash;
}

function get_user_id_by_mail($db, $email) {
    $sql = "SELECT id FROM users WHERE email = $1";
    $res = pg_query_params($db, $sql, array($email));
    
    return fetch($res);
}

?>
