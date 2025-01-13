<?php

require __DIR__ . '/../vendor/autoload.php';

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

// Configurações do ambiente
$clientId = 'AS2X9pz5SMA--ALLbIZ7FgIjNHT5lpGibYjcLDWcBffJkc0XX42xm6F1E4FGzJFWzP2SWggmGi2jL9Xs';
$clientSecret = 'EPo2s2lOTZzTr44OJc7qh4ZQJES5X3_UEsov9ChX7ReSzErx69YWKQ8mtJ_W91wn6nQWMasJJmPOERp0';

$environment = new SandboxEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($environment);

return $client;
