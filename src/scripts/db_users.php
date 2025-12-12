<?php

require_once "db_utils.php";
//Funzione per creare un utente dato nome, cognome, email e password
//(Ovvero le informazioni passate nella registrazione).
function create_user($db, $name, $surname, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(name, surname, email, password_hash)
            VALUES($1, $2, $3, $4)";

    //Esegui quel comando $sql e riempi i buchi $1, $2... con i dati che trovi 
    // in questo array
    $res = pg_query_params($db, $sql, array($name, $surname, $email, $hash));
    if (!$res) {
        echo "Errore: " . pg_last_error($db);
        return false;
    }

    return true;
}

function does_user_exist($db, $email) {
    $sql = "SELECT 1 
            FROM users
            WHERE email = $1";

    $res = pg_query_params($db, $sql, array($email));
    if (!$res) { return false; }

    return pg_num_rows($res) > 0;
}

function check_user_password($db, $email, $password) {
    $sql = "SELECT password_hash
            FROM users
            WHERE email = $1";

    $res = pg_query_params($db, $sql, array($email));

    $stored_hash = fetch_value($res);
    if (!$stored_hash) { return false; }

    return password_verify($password, $stored_hash);
}

function get_user_id_by_email($db, $email) {
    $sql = "SELECT id
            FROM users
            WHERE email = $1";

    $res = pg_query_params($db, $sql, array($email));

    return fetch_value($res);
}

function get_user_by_email($db, $email) {
    $sql = "SELECT *
            FROM users
            WHERE email = $1";

    $res = pg_query_params($db, $sql, array($email));
    return fetch_one($res);
}

function update_user_profile($db, $email, $year, $enrollment_year, $faculty, $preferred_time, $mode, $latitude, $longitude) {

    $sql = "UPDATE users 
            SET university_year = $1, 
                enrollment_year = $2,
                department = $3, 
                preferred_time = $4, 
                preferred_mode = $5,
                latitude = $7,
                longitude = $8
            WHERE email = $6";

    $res = pg_query_params($db, $sql, array($year, $enrollment_year, $faculty, $preferred_time, $mode, $email, $latitude, $longitude));

    return $res;
}
?>

