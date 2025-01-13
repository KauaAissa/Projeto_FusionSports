<?php

require __DIR__ . '/paypal.php';
require 'config.php'; // Inclui a conexão com o banco de dados
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

session_start();

header('Content-Type: application/json');

// Lê os dados enviados no corpo da requisição (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Valida os dados recebidos
$idEstabelecimento = $data['id_estabelecimento'] ?? null;
$idCliente = $data['id_cliente'] ?? null;
$dataReserva = $data['data_reserva'] ?? null;
$horarioInicio = $data['horario_inicio'] ?? null;
$horarioFim = $data['horario_fim'] ?? null;

if (!$idEstabelecimento || !$idCliente || !$dataReserva || !$horarioInicio || !$horarioFim) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
    exit;
}

// Consulta o valor da hora no banco de dados
$stmt = $pdo->prepare("SELECT valor_hora FROM estabelecimentos WHERE id_dono = ?");
$stmt->execute([$idEstabelecimento]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Estabelecimento não encontrado.']);
    exit;
}

$valorHora = $result['valor_hora'];

// Calcula a duração da reserva em horas
$horaInicio = strtotime($horarioInicio);
$horaFim = strtotime($horarioFim);
$duracaoHoras = ($horaFim - $horaInicio) / 3600;

// Calcula o valor total da reserva
$valorReserva = $valorHora * $duracaoHoras;

// Configura o cliente PayPal
$client = require 'paypal.php';

// URLs dinâmicas para ambiente
$baseUrl = "http://localhost/FusionSports/config";
$cancelUrl = $baseUrl . "/cancelado.php";
$returnUrl = $baseUrl . "/sucesso.php";

// Cria uma requisição para criar um pedido no PayPal
$request = new OrdersCreateRequest();
$request->prefer('return=representation');
$request->body = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "amount" => [
            "currency_code" => "BRL",
            "value" => number_format($valorReserva, 2, '.', '') // Formato correto
        ]
    ]],
    "application_context" => [
        "cancel_url" => $cancelUrl,
        "return_url" => $returnUrl
    ]
];

try {
    $response = $client->execute($request);

    // Salva os dados na sessão
    $_SESSION['id_estabelecimento'] = $idEstabelecimento;
    $_SESSION['id_cliente'] = $idCliente;
    $_SESSION['data_reserva'] = $dataReserva;
    $_SESSION['horario_inicio'] = $horarioInicio;
    $_SESSION['horario_fim'] = $horarioFim;

    // Retorna a URL para redirecionar o usuário ao PayPal
    echo json_encode(['success' => true, 'redirect_url' => $response->result->links[1]->href]);
} catch (Exception $e) {
    // Retorna o erro se ocorrer
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

error_log("Sessões configuradas: " . print_r($_SESSION, true));
