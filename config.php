<?php
$host = "localhost";
$db_name = "sakila";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

header("Content-Type: application/json; charset=UTF-8");
?>