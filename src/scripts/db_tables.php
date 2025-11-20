<?php

function create_users_table($db) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(20) NOT NULL ,
            surname VARCHAR(20) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            department VARCHAR(100),
            university_year INTEGER,
            enrollment_year INTEGER,
            preferred_mode VARCHAR(50),
            preferred_time VARCHAR(50),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    
    $ret = pg_query($db, $sql);
    if (!$ret) echo pg_last_error($db); 
}

function create_groups_table($db) {
    $sql = "CREATE TABLE IF NOT EXISTS groups (
            id SERIAL PRIMARY KEY,
            owner_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            name VARCHAR(50) NOT NULL UNIQUE,
            course VARCHAR(100) NOT NULL,
            subject VARCHAR(100) NOT NULL,
            description VARCHAR(255),
            is_public BOOLEAN DEFAULT FALSE,
            curr_members INTEGER DEFAULT 0,
            max_members INTEGER NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

    $ret = pg_query($db, $sql);
    if (!$ret) echo pg_last_error($db); 

    $sql2 = "CREATE TABLE IF NOT EXISTS group_participants (
            group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            PRIMARY KEY (group_id, user_id))";

    $ret2 = pg_query($db, $sql2);
    if (!$ret2) echo pg_last_error($db); 
}

?>
