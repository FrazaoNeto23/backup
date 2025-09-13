<?php
// Conexão com o banco
$host='localhost';$user='root';$pass='';$db='burger_house';
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error){die('Erro: '.$conn->connect_error);}