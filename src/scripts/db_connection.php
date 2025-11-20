<?php
<<<<<<< HEAD
//Informazioni sul db
$host = "localhost";
$port = '5432';
$dbname = 'gruppo15';
$username = 'www';
$password = 'tsw2023';
=======
    require_once "db_tables.php";
>>>>>>> 319db39dbcdbcd0795f99eb9170dac1768f30f6c

    $host = "localhost";
    $port = '5432';
    $dbname = 'postgres';
    $username = 'www';
    $password = '1919'; 

    $connection_string = "host=$host port=$port dbname=$dbname user=$username password=$password";
    $db = pg_connect($connection_string) or die('Impossibile connetersi');

    create_users_table($db);
    create_groups_table($db);
?>
