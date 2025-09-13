<?php
$host = 'localhost:3306';
$user = 'root';
$pass = '';
$db = 'hamburgueria';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>