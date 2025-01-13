<?php
include_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$id_estabelecimento = $_GET['id_estabelecimento'] ?? null;
$data_inicio = $_GET['data_inicio'] ?? null;
$data_fim = $_GET['data_fim'] ?? null;

if (!$id_estabelecimento || !$data_inicio || !$data_fim) {
    echo json_encode([]);
    exit;
}

$query = "SELECT horario_inicio, horario_fim, data_reserva, status 
          FROM reservas 
          WHERE id_estabelecimento = :id_estabelecimento 
          AND data_reserva BETWEEN :data_inicio AND :data_fim";

$stmt = $pdo->prepare($query);
$stmt->execute([
    ':id_estabelecimento' => $id_estabelecimento,
    ':data_inicio' => $data_inicio,
    ':data_fim' => $data_fim,
]);

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($reservas);
