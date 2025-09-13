<?php
$host = 'localhost:3307';
$user = 'root';
$pass = '';
$db = 'burger_house';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>