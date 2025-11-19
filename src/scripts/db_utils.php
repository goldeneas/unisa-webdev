<?php
    
function fetch($res) {
    if (!$res || pg_num_rows($res) <= 0) {
        return false;
    }

    return pg_fetch_assoc($res);
}

?>
