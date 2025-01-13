<?php

include_once 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['password']) || empty($_POST['confirm_password']) || empty($_POST['establishment_name']) || empty($_POST['establishment_address']) || empty($_POST['inicioFuncionamento']) || empty($_POST['terminoFuncionamento']) || empty($_POST['valor_hora'])) {

        header('Location: ../estabelecimento-cadastro.php?error=campos_vazios');
        exit();
    }

    $nome = $_POST['name'];
    $email = $_POST['email'];
    $telefone = $_POST['phone'];
    $senha = $_POST['password'];
    $confirmSenha = $_POST['confirm_password'];
    $nome_estabelecimento = $_POST['establishment_name'];
    $cidade = $_POST['establishment_address'];
    $descricao = $_POST['description'];
    $inicioFuncionamento = $_POST['inicioFuncionamento'];
    $terminoFuncionamento = $_POST['terminoFuncionamento'];
    $valorHora = $_POST['valor_hora'];

    // Verificar se as senhas coincidem
    if ($senha !== $confirmSenha) {
        // Redirecionar de volta para o cadastro com mensagem de erro
        header('Location: ../estabelecimento-cadastro.php?error=senhas_nao_coincidem');
        exit();
    }

    // Criptografar a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir o dono no banco de dados
    $sqlDono = "INSERT INTO donos (nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)";
    $stmtDono = $pdo->prepare($sqlDono);
    $stmtDono->bindParam(':nome', $nome);
    $stmtDono->bindParam(':email', $email);
    $stmtDono->bindParam(':telefone', $telefone);
    $stmtDono->bindParam(':senha', $senhaHash);
    $stmtDono->execute();

    // Recuperar o ID do dono inserido
    $idDono = $pdo->lastInsertId();

    // Inserir o estabelecimento no banco de dados com os horários duplicados para todos os dias
    $sqlEstabelecimento = "
    INSERT INTO estabelecimentos (
        nome_estabelecimento, cidade, descricao, id_dono, valor_hora,
        hora_abertura_seg, hora_fechamento_seg, 
        hora_abertura_ter, hora_fechamento_ter, 
        hora_abertura_qua, hora_fechamento_qua, 
        hora_abertura_qui, hora_fechamento_qui, 
        hora_abertura_sex, hora_fechamento_sex, 
        hora_abertura_sab, hora_fechamento_sab, 
        hora_abertura_dom, hora_fechamento_dom
    ) 
    VALUES (
        :nome_estabelecimento, :cidade, :descricao, :id_dono, :valor_hora,
        :inicioFuncionamento, :terminoFuncionamento, 
        :inicioFuncionamento, :terminoFuncionamento, 
        :inicioFuncionamento, :terminoFuncionamento, 
        :inicioFuncionamento, :terminoFuncionamento, 
        :inicioFuncionamento, :terminoFuncionamento, 
        :inicioFuncionamento, :terminoFuncionamento, 
        :inicioFuncionamento, :terminoFuncionamento
    )";

    $stmtEstabelecimento = $pdo->prepare($sqlEstabelecimento);
    $stmtEstabelecimento->bindParam(':nome_estabelecimento', $nome_estabelecimento);
    $stmtEstabelecimento->bindParam(':cidade', $cidade);
    $stmtEstabelecimento->bindParam(':descricao', $descricao);
    $stmtEstabelecimento->bindParam(':id_dono', $idDono);
    $stmtEstabelecimento->bindParam(':valor_hora', $valorHora);
    $stmtEstabelecimento->bindParam(':inicioFuncionamento', $inicioFuncionamento);
    $stmtEstabelecimento->bindParam(':terminoFuncionamento', $terminoFuncionamento);
    $stmtEstabelecimento->execute();

    // Redirecionar para a página de login após cadastro bem-sucedido
    header('Location: ../estabelecimento-login.php');
    exit();
} else {
    // Caso não seja via POST, redirecionar para o cadastro
    header('Location: ../estabelecimento-cadastro.php');
    exit();
}
