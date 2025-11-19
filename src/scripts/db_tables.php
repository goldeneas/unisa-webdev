<?php

function create_users_table($db) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        nome NOT NULL VARCHAR(20),
        cognome NOT NULL VARCHAR(20),
        email NOT NULL VARCHAR(50),
        password_hash NOT NULL VARCHAR(255),
        department VARCHAR(100),
        university_year INTEGER,
        enrollment_year INTEGER,
        preferred_mode VARCHAR(50),
        preferred_time VARCHAR(50),
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $ret = pg_query($db, $sql);
    if (!$ret) echo pg_last_error($db); 
}

function create_groups_table($db) {
    $sql = "CREATE TABLE IF NOT EXISTS groups (
        id SERIAL PRIMARY KEY,
        owner_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        name NOT NULL VARCHAR(50),
        course NOT NULL VARCHAR(100),
        subject NOT NULL VARCHAR(100),
        description VARCHAR(255),
        is_public BOOLEAN DEFAULT FALSE,
        curr_members INTEGER DEFAULT 0,
        max_members NOT NULL INTEGER,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $ret = pg_query($db, $sql);
    if (!$ret) echo pg_last_error($db); 

    $sql2 = "CREATE TABLE IF NOT EXISTS group_participants (
        group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY (group_id, user_id)
    )";

    $ret2 = pg_query($db, $sql2);
    if (!$ret2) echo pg_last_error($db); 
}

?>
