<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esame";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
