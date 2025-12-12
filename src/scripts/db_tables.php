<?php
//Funzione per creare per la prima volta una tabella per le informazioni
//relative ad ogni utente.
//N.B.: IF NOT EXITS -> crea la tabella solo se non c'è già
     // UNIQUE -> Impedisce che due persone si registrino con la stessa email. 
     // NOT NULL -> Quel campo non può essere avere un valore null.
function create_users_table($db) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(20) NOT NULL ,
            surname VARCHAR(20) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            department VARCHAR(100),
            university_year VARCHAR(20),
            enrollment_year INTEGER,
            preferred_mode VARCHAR(50),
            preferred_time VARCHAR(50),
            latitude NUMERIC(10, 7),
            longitude NUMERIC(10, 7),
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

            //id è diverso dal nome, viene messo a prescindere dal db

            //password_hash è l'hash della password, in modo tale da non far viaggiare
            // la vera pass. in rete che qualcuno potrebbe vedere e rubare.

            //timestamp = data in cui è stato inserita la entry 

    //Se la tabella viene creata correttamente (o era già stata creata), 
    // ret conterrà true. In caso di errori, conterrà false.
    $ret = pg_query($db, $sql);

    //Controllo se la creazione è andata a buon fine -> ret deve essere true.
    //In caso contrario, significa che la struttura del database non è stata creata. 
    // Senza questa tabella, nessuna registrazione o login sarebbe effettuata.
    if (!$ret) echo pg_last_error($db); 
}

// Ragionamento uguale per create_user_table, ma ora è per una tabella di gruppi ***
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

            //REFERENCES users(id) -> Il valore di owner_id deve corrispondere 
            // obbligatoriamente a un id che esiste davvero nella tabella users

            //ON DELETE CASCADE -> Se cancelli owner_id, automaticamente il database
            // cancellerà anche tutti i gruppi creati da lui e tutte le righe dove lui
            //  compare come partecipante.

    $ret = pg_query($db, $sql);
    if (!$ret) echo pg_last_error($db); 

// ***

        //Logica uguale alla precedente, ma tabella che lega un gruppo a un utente.
        //In questo modo è possibile che un utente sia partecipe a più gruppi, ma
        // ma lo stesso utente non può essere "presente" più volte in uno stesso gruppo
    $sql2 = "CREATE TABLE IF NOT EXISTS group_participants (
            group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            PRIMARY KEY (group_id, user_id))";

    $ret2 = pg_query($db, $sql2);
    if (!$ret2) echo pg_last_error($db); 
}

?>