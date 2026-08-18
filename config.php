<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alchemy_of_apollo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>