<?php
//Informazioni sul db
$host = "localhost";
$port = '5432';
$dbname = 'gruppo15';
$username = 'www';
$password = 'tsw2023';

$connection_string = "host=$host port=$port dbname=$dbname user=$username password=$password";
$db = pg_connect($connection_string) or die('Impossibile connetersi');
?>
