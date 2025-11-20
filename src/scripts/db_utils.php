<?php
    
function fetch_one($res) {
    if (!$res || pg_num_rows($res) <= 0) {
        return false;
    }

    return pg_fetch_assoc($res);
}

function fetch_value($res) {
    if (!$res || pg_num_rows($res) <= 0) {
        return false;
    }

    return pg_fetch_result($res, 0, 0);
}

function fetch_all($res) {
    if (!$res || pg_num_rows($res) <= 0) {
        return array();
    }

    $data = pg_fetch_all($res);
    return $data ? $data : array();
}

?>
