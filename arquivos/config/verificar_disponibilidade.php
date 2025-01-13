<?php


include_once 'config/config.php';

if (isset($_GET['id_estabelecimento']) && isset($_GET['data_reserva'])) {
    $id_estabelecimento = $_GET['id_estabelecimento'];
    $data_reserva = $_GET['data_reserva'];

    // Consulta para obter os horários reservados
    $stmt = $pdo->prepare("SELECT horario_inicio, horario_fim FROM reservas WHERE id_estabelecimento = :id_estabelecimento AND data_reserva = :data_reserva AND status = 'confirmada'");
    $stmt->execute(['id_estabelecimento' => $id_estabelecimento, 'data_reserva' => $data_reserva]);
    $horariosReservados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os horários em formato JSON
    echo json_encode($horariosReservados);
} else {
    echo json_encode(["error" => "Parâmetros inválidos"]);
}
