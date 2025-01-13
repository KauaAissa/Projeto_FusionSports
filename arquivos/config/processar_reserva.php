<?php

include_once 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_reserva'], $_POST['acao'])) {
    $id_reserva = $_POST['id_reserva'];
    $acao = $_POST['acao'];


    if ($acao == 'confirmar') {

        $stmt = $pdo->prepare("UPDATE reservas SET status = 'confirmada' WHERE id_reserva = :id_reserva");
        $stmt->execute(['id_reserva' => $id_reserva]);
        $mensagem = "Reserva confirmada com sucesso!";
    } elseif ($acao == 'rejeitar') {

        $stmt = $pdo->prepare("DELETE FROM reservas WHERE id_reserva = :id_reserva");
        $stmt->execute(['id_reserva' => $id_reserva]);
        $mensagem = "Reserva deletada com sucesso!";
    }


    header("Location: ../reservas-pendentes.php?mensagem=" . urlencode($mensagem));
    exit();
} else {

    header("Location: ../reservas-pendentes.php?mensagem=" . urlencode("Ação inválida."));
    exit();
}
