<?php
session_start();
include_once("templates/header-login-cadastro-usuario.php");
include_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $erro = "O e-mail já está registrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senhaHash);

            if ($stmt->execute()) {
                $mensagemSucesso = "Cadastro realizado com sucesso! Faça login.";
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_cliente'] = $usuario['id_cliente'];
            $_SESSION['nome'] = $usuario['nome'];
            header("Location: finalizar-agendamento.php");
            exit();
        } else {
            $erroLogin = "E-mail ou senha inválidos.";
        }
    }
}
?>

<!-- HTML principal -->
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form action="login-cadastro-usuario.php" method="POST" onsubmit="exibirLoader()">
            <h1>Crie sua conta!</h1>
            <span>Use seu email para se registrar</span>
            <input type="text" name="nome" placeholder="Nome" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="senha" placeholder="Senha" required />
            <button type="submit" name="signup">Cadastrar</button>
            <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
            <?php if (isset($mensagemSucesso)) echo "<p style='color: green;'>$mensagemSucesso</p>"; ?>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="login-cadastro-usuario.php" method="POST" onsubmit="exibirLoader()">
            <h1>Login</h1>
            <span>Preencha com suas informações abaixo</span>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="senha" placeholder="Senha" required />
            <button type="submit" name="login">Logar</button>
            <?php if (isset($erroLogin)) echo "<p style='color: red;'>$erroLogin</p>"; ?>
        </form>
    </div>

    <!-- Overlay Container -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Bem vindo de volta!</h1>
                <p>Pra continuar conectado conosco logue com suas informações pessoais</p>
                <button class="ghost" id="signIn">Logar</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Seja bem vindo!</h1>
                <p>Pra se cadastrar é necessário inserir suas informações pessoais</p>
                <button class="ghost" id="signUp">Cadastre-se</button>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div id="fullscreen-loading" style="display: none;">
    <div class="loading-spinner"></div>
</div>

<script src="js/update.js"></script>

<script>
    // Função para exibir o loader
    function exibirLoader() {
        document.getElementById("fullscreen-loading").style.display = "flex";
    }


    document.querySelector(".text-name").addEventListener("click", function(event) {
        event.preventDefault();
        exibirLoader();
        setTimeout(() => {
            window.location.href = "index.php";
        }, 2000);
    });
</script>

<?php include_once("templates/footer.php"); ?>