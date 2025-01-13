<?php
// Configurações do banco de dados
$host = 'localhost';
$db_name = 'fusion_sports';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$db_name";

try {
    // Criar uma instância PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
