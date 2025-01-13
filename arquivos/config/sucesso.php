<?php
require 'paypal.php';
require 'config.php';

use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;


session_start();


error_log("Sucesso.php iniciado");
error_log("GET Parameters: " . print_r($_GET, true));
error_log("Session Data: " . print_r($_SESSION, true));


if (isset($_GET['token']) && isset($_GET['PayerID'])) {
    $token = $_GET['token'];
    $payerId = $_GET['PayerID'];

    error_log("Token: $token");
    error_log("PayerID: $payerId");

    // Captura os detalhes do pagamento
    $request = new OrdersCaptureRequest($token);

    try {
        $client = require 'paypal.php';
        $response = $client->execute($request);

        // Log da resposta do PayPal
        error_log("PayPal Response: " . print_r($response, true));

        if ($response->statusCode === 201 || $response->statusCode === 200) {
            // Recupera os dados da sessão para a reserva
            $idEstabelecimento = $_SESSION['id_estabelecimento'] ?? null;
            $idCliente = $_SESSION['id_cliente'] ?? null;
            $dataReserva = $_SESSION['data_reserva'] ?? null;
            $horarioInicio = $_SESSION['horario_inicio'] ?? null;
            $horarioFim = $_SESSION['horario_fim'] ?? null;

            // Verifica se todos os dados necessários estão disponíveis
            if (!$idEstabelecimento || !$idCliente || !$dataReserva || !$horarioInicio || !$horarioFim) {
                throw new Exception('Dados da reserva incompletos.');
            }

            // Insere a reserva no banco de dados
            $stmt = $pdo->prepare("
                INSERT INTO reservas (id_estabelecimento, id_cliente, data_reserva, horario_inicio, horario_fim, status) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $idEstabelecimento,
                $idCliente,
                $dataReserva,
                $horarioInicio,
                $horarioFim,
                'confirmada'
            ]);

            // Redireciona para a página de reservas
            header('Location: ../reservas.php');
            exit;
        } else {
            throw new Exception('Erro ao capturar o pagamento: ' . $response->statusCode);
        }
    } catch (Exception $e) {
        // Log do erro
        error_log("Erro ao processar pagamento: " . $e->getMessage());

        // Exibe mensagem de erro amigável
        echo "<h1>Erro no pagamento</h1>";
        echo "<p>Não foi possível processar o pagamento. Por favor, tente novamente.</p>";
        echo "<a href='/'>Voltar à página inicial</a>";
    }
} else {
    error_log("Token ou PayerID ausente");

    // Exibe mensagem de erro para parâmetros ausentes
    echo "<h1>Erro no pagamento</h1>";
    echo "<p>Dados incompletos. Por favor, tente novamente.</p>";
    echo "<a href='/'>Voltar à página inicial</a>";
}


error_log("Sessões disponíveis no sucesso.php: " . print_r($_SESSION, true));
