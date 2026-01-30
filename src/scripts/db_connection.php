<?php
require_once "db_tables.php";

//Informazioni sul db

$host = "localhost";
$port = '5432';
$dbname = 'gruppo10';
$username = 'www';
$password = 'www';

$connection_string = "host=$host port=$port dbname=$dbname user=$username password=$password";
$db = pg_connect($connection_string) or die('Impossibile connettersi');

create_users_table($db);
create_groups_table($db);
