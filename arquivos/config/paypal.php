<?php

require __DIR__ . '/../vendor/autoload.php';

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

// Configurações do ambiente
$clientId = 'ADICIONE SUA CLIENT ID AQUI';
$clientSecret = 'ADICIONE SUA CLIENT SECRET AQUI';

$environment = new SandboxEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($environment);

return $client;
