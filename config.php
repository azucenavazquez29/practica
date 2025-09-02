<?php
$host = "localhost";
$db_name = "sakila";
$username = "19110097";
$password = "19110097";

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

header("Content-Type: application/json; charset=UTF-8");
?>