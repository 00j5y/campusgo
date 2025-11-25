<?php
$user = "root";
$mdp = "1234";
$host = "localhost";
$port = "3306";
$dbname = "r301";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",$user,$mdp
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
