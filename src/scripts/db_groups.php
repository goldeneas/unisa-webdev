<?php
require_once "db_utils.php";
require_once "db_users.php";

function create_group($db, $name, $course, $subject, $max_members, $description, $is_public,
    $owner_email) {

    $owner_id = get_user_id_by_email($db, $owner_email);
    $sql = "INSERT INTO groups(name, course, subject, max_members, description, is_public, owner_id) 
            VALUES($1, $2, $3, $4, $5, $6, $7)";

    pg_query_params($db, $sql, array($name, $course, $subject,
        $max_members, $description, $is_public, $owner_id));

    return true;
}

function get_group_with_id($db, $id) {
    $sql = "SELECT * 
            FROM groups
            WHERE id = $1";

    $res = pg_query_params($db, $sql, array($id));

    return fetch_one($res);
}

function get_groups_starting_with($db, $str) {
    $sql = "SELECT * 
            FROM groups
            WHERE name 
            LIKE $1";

    $res = pg_query_params($db, $sql, array($str . "%"));

    return fetch_all($res);
}

function get_group_id_by_name($db, $name) {
    $sql = "SELECT id
            FROM groups
            WHERE name = $1";

    $res = pg_query_params($db, $sql, array($name));

    return fetch_value($res);
}

function add_user_to_group($db, $group_name, $user_email) {
    $group_id = get_group_id_by_name($db, $group_name);
    $user_id = get_user_id_by_email($db, $user_email);

    $sql = "INSERT INTO group_participants(group_id, user_id)
            VALUES($1, $2)";

    pg_query_params($db, $sql, array($group_id, $user_id));
}

function get_users_in_group($db, $group_name) {
    $group_id = get_group_id_by_name($db, $group_name);
    
    $sql = "SELECT u.id, u.name, u.surname, u.email 
            FROM group_participants gp
            JOIN users u ON gp.user_id = u.id
            WHERE gp.group_id = $1"; 
    
    $res = pg_query_params($db, $sql, array($group_id));
    return fetch_all($res);
}

function delete_group($db, $group_id) {
    $sql_participants = "DELETE FROM group_participants 
                         WHERE group_id = $1";
    
    pg_query_params($db, $sql_participants, array($group_id));

    $sql_group = "DELETE FROM groups 
                  WHERE id = $1";

    $res = pg_query_params($db, $sql_group, array($group_id));

    return ($res !== false);
}

?>
