<?php
session_start();

include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['password'];


    $stmt = $pdo->prepare("SELECT * FROM donos WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && password_verify($senha, $user['senha'])) {

        $_SESSION['id_dono'] = $user['id_dono'];
        $_SESSION['nome_dono'] = $user['nome'];

        header('Location: ../gerenciamento-quadras.php');
        exit();
    } else {

        header('Location: ../estabelecimento-login.php?error=1');
        exit();
    }
} else {

    header('Location: ../estabelecimento-login.php');
    exit();
}
