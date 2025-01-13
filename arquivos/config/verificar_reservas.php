<?php
include_once 'config.php';

$currentDateTime = new DateTime();

// Consulta para buscar todas as reservas com status "confirmada" ou "pendente"
$stmt = $pdo->prepare("SELECT id_reserva, data_reserva, horario_fim, status FROM reservas WHERE status IN ('confirmada', 'pendente')");
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$reservasFinalizadas = 0;
$reservasCanceladas = 0;

foreach ($reservas as $reserva) {

    $reservaDateTime = new DateTime($reserva['data_reserva'] . ' ' . $reserva['horario_fim']);

    // Verifica se a data e o horário da reserva já passaram
    if ($currentDateTime > $reservaDateTime) {
        if ($reserva['status'] === 'confirmada') {
            // Finaliza a reserva confirmada
            $stmtUpdate = $pdo->prepare("UPDATE reservas SET status = 'finalizada' WHERE id_reserva = :id_reserva");
            $stmtUpdate->execute(['id_reserva' => $reserva['id_reserva']]);
            $reservasFinalizadas++;
        } elseif ($reserva['status'] === 'pendente') {
            // Cancela a reserva pendente
            $stmtDelete = $pdo->prepare("DELETE FROM reservas WHERE id_reserva = :id_reserva");
            $stmtDelete->execute(['id_reserva' => $reserva['id_reserva']]);
            $reservasCanceladas++;
        }
    }
}

// Resposta JSON para a requisição AJAX
if ($reservasFinalizadas > 0 || $reservasCanceladas > 0) {
    echo json_encode([
        'success' => true,
        'message' => "Reservas finalizadas: $reservasFinalizadas, Reservas canceladas: $reservasCanceladas."
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => "Nenhuma reserva foi finalizada ou cancelada."
    ]);
}
