<?php

include_once 'config.php';


session_start();
if (!isset($_SESSION['id_dono'])) {
    header("Location: estabelecimento-login.php");
    exit();
}

$id_dono = $_SESSION['id_dono'];


if (isset($_POST['email-atual']) && isset($_POST['novo-email'])) {
    $emailAtual = $_POST['email-atual'];
    $novoEmail = $_POST['novo-email'];


    $stmt = $pdo->prepare("SELECT email FROM donos WHERE id_dono = :id_dono");
    $stmt->execute(['id_dono' => $id_dono]);
    $dono = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dono && $dono['email'] === $emailAtual) {

        $stmt = $pdo->prepare("UPDATE donos SET email = :novo_email WHERE id_dono = :id_dono");
        $stmt->execute(['novo_email' => $novoEmail, 'id_dono' => $id_dono]);


        header("Location: ../perfil.php?success=email_atualizado");
        exit();
    } else {

        header("Location: ../perfil.php?error=email_incorreto");
        exit();
    }
}


if (isset($_POST['senha-atual']) && isset($_POST['nova-senha']) && isset($_POST['confirmar-senha'])) {
    $senhaAtual = $_POST['senha-atual'];
    $novaSenha = $_POST['nova-senha'];
    $confirmarSenha = $_POST['confirmar-senha'];


    if ($novaSenha !== $confirmarSenha) {

        header("Location: ../perfil.php?error=senhas_nao_coincidem");
        exit();
    }


    $stmt = $pdo->prepare("SELECT senha FROM donos WHERE id_dono = :id_dono");
    $stmt->execute(['id_dono' => $id_dono]);
    $dono = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dono && password_verify($senhaAtual, $dono['senha'])) {

        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE donos SET senha = :nova_senha WHERE id_dono = :id_dono");
        $stmt->execute(['nova_senha' => $novaSenhaHash, 'id_dono' => $id_dono]);


        header("Location: ../perfil.php?success=senha_atualizada");
        exit();
    } else {

        header("Location: ../perfil.php?error=senha_incorreta");
        exit();
    }
}


header("Location: ../perfil.php");
exit();
