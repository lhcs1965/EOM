<?php
$host ="localhost";
// $host = "192.168.15.178";
$port = 3306;
$name = "eom";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4", $user, $pass);//;charset=utf8mb4
    $conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $err) {
    echo 'Erro na conexão com o banco de dados: ' . $err->getMessage();
    exit;
}
