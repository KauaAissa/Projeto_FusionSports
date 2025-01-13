<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mensagem = htmlspecialchars(trim($_POST['message']));


    if (empty($nome) || empty($email) || empty($mensagem)) {

        echo "Por favor, preencha todos os campos obrigatórios.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "Por favor, insira um e-mail válido.";
        exit;
    }

    // Destinatário do e-mail (seu e-mail pessoal)
    $destinatario = "fusion.sports.site@gmail.com";

    // Assunto do e-mail
    $assunto = "Nova mensagem de contato do site FusionSports";

    // Corpo do e-mail
    $corpo = "Você recebeu uma nova mensagem do site FusionSports.\n\n";
    $corpo .= "Nome: $nome\n";
    $corpo .= "E-mail: $email\n\n";
    $corpo .= "Mensagem:\n$mensagem\n";

    // Cabeçalhos do e-mail
    $headers = "From: fusion.sports.site@gmail.com\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Enviar o e-mail
    if (mail($destinatario, $assunto, $corpo, $headers)) {

        header("Location: ../index.php?status=sucesso");
        exit;
    } else {

        header("Location: ../index.php?status=erro");
        exit;
    }
} else {

    header("Location: ../index.php");
    exit;
}
