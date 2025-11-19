<?php
require_once "db_utils.php"
require_once "db_users.php";

function create_group($db, $name, $course, $subject, $description, $is_public, $owner_email) {
    $owner_id = get_user_id_by_mail($db, $owner_mail);
    $sql = "INSERT INTO groups(name, course, subject, description, is_public, owner_id) 
        VALUES($1, $2)";

    pg_query_params($db, $sql, array($name, $course, $subject,
        $description, $is_public, $owner_id));
}

function get_group_with_id($db, $id) {
    $sql = "SELECT * FROM groups WHERE id = $1";
    $res = pg_query_params($db, $sql, array($id));

    return fetch($res);
}

function get_groups_starting_with($db, $str) {
    $sql = "SELECT * FROM groups WHERE name LIKE '$1%'";
    $res = pg_query_params($db, $sql, array($str));

    return fetch($res);
}

?>
