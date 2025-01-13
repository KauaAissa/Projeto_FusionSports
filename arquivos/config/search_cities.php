<?php
include_once 'config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Preparar a consulta para buscar cidades que correspondam ao termo
    $stmt = $pdo->prepare("SELECT DISTINCT cidade FROM estabelecimentos WHERE cidade LIKE :query");
    $stmt->execute(['query' => '%' . $query . '%']);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar as cidades em formato JSON
    echo json_encode(array_column($resultados, 'cidade'));
}
