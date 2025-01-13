<?php
include_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_SESSION['id_cliente'] ?? null;
    $id_estabelecimento = $_POST['id_estabelecimento'] ?? null;
    $data_reserva = $_POST['data_reserva'] ?? null;
    $horario_inicio = $_POST['horario_inicio'] ?? null;
    $horario_fim = $_POST['horario_fim'] ?? null;
    $metodo_pagamento = $_POST['metodo_pagamento'] ?? null;

    if (!$id_cliente || !$id_estabelecimento || !$data_reserva || !$horario_inicio || !$horario_fim || !$metodo_pagamento) {
        echo 'Parâmetros faltando';
        exit;
    }

    try {
        // Verificar se o cliente já possui reservas pendentes
        if ($metodo_pagamento === 'dinheiro') {
            $checkQuery = "SELECT COUNT(*) FROM reservas 
                           WHERE id_cliente = :id_cliente AND status = 'pendente'";
            $stmt = $pdo->prepare($checkQuery);
            $stmt->execute([':id_cliente' => $id_cliente]);

            $reservasPendentes = $stmt->fetchColumn();

            if ($reservasPendentes > 0) {
                echo 'Você já possui uma reserva pendente. Espere a confirmação do estabelecimento antes de fazer outra.';
                exit;
            }
        }

        // Buscar valor_hora do estabelecimento
        $valorQuery = "SELECT valor_hora FROM estabelecimentos WHERE id_estabelecimento = :id_estabelecimento";
        $stmt = $pdo->prepare($valorQuery);
        $stmt->execute([':id_estabelecimento' => $id_estabelecimento]);
        $valor_hora = $stmt->fetchColumn();

        if (!$valor_hora) {
            echo 'Erro: Estabelecimento não encontrado';
            exit;
        }

        // Calcular valor da reserva
        $hora_inicio = new DateTime($horario_inicio);
        $hora_fim = new DateTime($horario_fim);
        $intervalo = $hora_inicio->diff($hora_fim);
        $horas = $intervalo->h;
        $valor_pago = $valor_hora * $horas;

        // Verificar disponibilidade do horário (incluindo reservas pendentes e confirmadas)
        $query = "SELECT * FROM reservas 
                  WHERE id_estabelecimento = :id_estabelecimento 
                  AND data_reserva = :data_reserva 
                  AND ((horario_inicio < :horario_fim AND horario_fim > :horario_inicio) 
                  OR (horario_inicio >= :horario_inicio AND horario_inicio < :horario_fim)) 
                  AND status IN ('pendente', 'confirmada')";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_estabelecimento' => $id_estabelecimento,
            ':data_reserva' => $data_reserva,
            ':horario_fim' => $horario_fim,
            ':horario_inicio' => $horario_inicio,
        ]);

        if ($stmt->rowCount() > 0) {
            echo 'Horário já reservado';
            exit;
        }

        // Determinar o status com base no método de pagamento
        $status = ($metodo_pagamento === 'dinheiro') ? 'pendente' : 'confirmada';

        // Inserir nova reserva
        $insertQuery = "INSERT INTO reservas 
                        (id_estabelecimento, id_cliente, data_reserva, horario_inicio, horario_fim, status) 
                        VALUES (:id_estabelecimento, :id_cliente, :data_reserva, :horario_inicio, :horario_fim, :status)";

        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([
            ':id_estabelecimento' => $id_estabelecimento,
            ':id_cliente' => $id_cliente,
            ':data_reserva' => $data_reserva,
            ':horario_inicio' => $horario_inicio,
            ':horario_fim' => $horario_fim,
            ':status' => $status,
        ]);

        echo 'success';
    } catch (PDOException $e) {
        echo 'Erro ao salvar reserva: ' . $e->getMessage();
    }
}
