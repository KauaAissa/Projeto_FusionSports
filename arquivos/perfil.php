<?php
include_once("templates/header-perfil.php");
include_once 'config/config.php';

// Verifica se o dono está logado
session_start();
if (!isset($_SESSION['id_dono'])) {
    header("Location: estabelecimento-login.php");
    exit();
}

$id_dono = $_SESSION['id_dono'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $valorHora = $_POST['valor_hora'] ?? 0;

    // Captura os horários de funcionamento
    $hora_abertura_seg = $_POST['hora_abertura_seg'] ?? null;
    $hora_fechamento_seg = $_POST['hora_fechamento_seg'] ?? null;

    $hora_abertura_ter = $_POST['hora_abertura_ter'] ?? null;
    $hora_fechamento_ter = $_POST['hora_fechamento_ter'] ?? null;

    $hora_abertura_qua = $_POST['hora_abertura_qua'] ?? null;
    $hora_fechamento_qua = $_POST['hora_fechamento_qua'] ?? null;

    $hora_abertura_qui = $_POST['hora_abertura_qui'] ?? null;
    $hora_fechamento_qui = $_POST['hora_fechamento_qui'] ?? null;

    $hora_abertura_sex = $_POST['hora_abertura_sex'] ?? null;
    $hora_fechamento_sex = $_POST['hora_fechamento_sex'] ?? null;

    $hora_abertura_sab = $_POST['hora_abertura_sab'] ?? null;
    $hora_fechamento_sab = $_POST['hora_fechamento_sab'] ?? null;

    $hora_abertura_dom = $_POST['hora_abertura_dom'] ?? null;
    $hora_fechamento_dom = $_POST['hora_fechamento_dom'] ?? null;

    // Atualiza os dados no banco de dados
    $stmt = $pdo->prepare("UPDATE estabelecimentos SET 
        nome_estabelecimento = :nome, 
        descricao = :descricao,
        valor_hora = :valor_hora,
        hora_abertura_seg = :hora_abertura_seg, hora_fechamento_seg = :hora_fechamento_seg,
        hora_abertura_ter = :hora_abertura_ter, hora_fechamento_ter = :hora_fechamento_ter,
        hora_abertura_qua = :hora_abertura_qua, hora_fechamento_qua = :hora_fechamento_qua,
        hora_abertura_qui = :hora_abertura_qui, hora_fechamento_qui = :hora_fechamento_qui,
        hora_abertura_sex = :hora_abertura_sex, hora_fechamento_sex = :hora_fechamento_sex,
        hora_abertura_sab = :hora_abertura_sab, hora_fechamento_sab = :hora_fechamento_sab,
        hora_abertura_dom = :hora_abertura_dom, hora_fechamento_dom = :hora_fechamento_dom
        WHERE id_dono = :id_dono");
    $stmt->execute([
        'nome' => $nome,
        'descricao' => $descricao,
        'valor_hora' => $valorHora,
        'hora_abertura_seg' => $hora_abertura_seg,
        'hora_fechamento_seg' => $hora_fechamento_seg,
        'hora_abertura_ter' => $hora_abertura_ter,
        'hora_fechamento_ter' => $hora_fechamento_ter,
        'hora_abertura_qua' => $hora_abertura_qua,
        'hora_fechamento_qua' => $hora_fechamento_qua,
        'hora_abertura_qui' => $hora_abertura_qui,
        'hora_fechamento_qui' => $hora_fechamento_qui,
        'hora_abertura_sex' => $hora_abertura_sex,
        'hora_fechamento_sex' => $hora_fechamento_sex,
        'hora_abertura_sab' => $hora_abertura_sab,
        'hora_fechamento_sab' => $hora_fechamento_sab,
        'hora_abertura_dom' => $hora_abertura_dom,
        'hora_fechamento_dom' => $hora_fechamento_dom,
        'id_dono' => $id_dono
    ]);

    // Redireciona para a mesma página após salvar para recarregar os dados
    header("Location: perfil.php");
    exit();
}

// Busca os dados do estabelecimento para exibir no formulário
$stmt = $pdo->prepare("SELECT nome_estabelecimento, descricao, valor_hora,
    hora_abertura_seg, hora_fechamento_seg,
    hora_abertura_ter, hora_fechamento_ter,
    hora_abertura_qua, hora_fechamento_qua,
    hora_abertura_qui, hora_fechamento_qui,
    hora_abertura_sex, hora_fechamento_sex,
    hora_abertura_sab, hora_fechamento_sab,
    hora_abertura_dom, hora_fechamento_dom
    FROM estabelecimentos WHERE id_dono = :id_dono");
$stmt->execute(['id_dono' => $id_dono]);
$estabelecimento = $stmt->fetch(PDO::FETCH_ASSOC);

// Preenche os dados no formulário
$nome_estabelecimento = $estabelecimento['nome_estabelecimento'] ?? '';
$descricao = $estabelecimento['descricao'] ?? '';
$valor_hora = $estabelecimento['valor_hora'] ?? '';

$hora_abertura_seg = $estabelecimento['hora_abertura_seg'] ?? '';
$hora_fechamento_seg = $estabelecimento['hora_fechamento_seg'] ?? '';

$hora_abertura_ter = $estabelecimento['hora_abertura_ter'] ?? '';
$hora_fechamento_ter = $estabelecimento['hora_fechamento_ter'] ?? '';

$hora_abertura_qua = $estabelecimento['hora_abertura_qua'] ?? '';
$hora_fechamento_qua = $estabelecimento['hora_fechamento_qua'] ?? '';

$hora_abertura_qui = $estabelecimento['hora_abertura_qui'] ?? '';
$hora_fechamento_qui = $estabelecimento['hora_fechamento_qui'] ?? '';

$hora_abertura_sex = $estabelecimento['hora_abertura_sex'] ?? '';
$hora_fechamento_sex = $estabelecimento['hora_fechamento_sex'] ?? '';

$hora_abertura_sab = $estabelecimento['hora_abertura_sab'] ?? '';
$hora_fechamento_sab = $estabelecimento['hora_fechamento_sab'] ?? '';

$hora_abertura_dom = $estabelecimento['hora_abertura_dom'] ?? '';
$hora_fechamento_dom = $estabelecimento['hora_fechamento_dom'] ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['foto_principal'])) {
        $fotoPrincipal = $_FILES['foto_principal'];
    }

    if (isset($_FILES['fotos_adicionais'])) {
        foreach ($_FILES['fotos_adicionais']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['fotos_adicionais']['error'][$index] === UPLOAD_ERR_OK) {
            }
        }
    }
}



if (isset($_GET['upload_success'])) {
    echo "<p style='color: green; text-align: center;'>Imagens salvas com sucesso!</p>";
}


if (isset($_GET['upload_error'])) {
    $errorMessage = urldecode($_GET['upload_error']);
    echo "<p style='color: red; text-align: center;'>Erro ao salvar imagens: $errorMessage</p>";
}



?>

<!-- Conteúdo da Página -->
<main class="dashboard">
    <!-- Seção: Informações do Estabelecimento -->
    <section id="info-estabelecimento" class="dashboard-section active-section">
        <h2>Informações do Estabelecimento</h2>
        <form class="form-estabelecimento" method="POST" id="form-estabelecimento">
            <!-- Nome do Estabelecimento -->
            <label for="nome">Nome do Estabelecimento:</label>
            <input
                type="text"
                id="nome"
                name="nome"
                value="<?php echo htmlspecialchars($nome_estabelecimento); ?>"
                placeholder="Nome do Estabelecimento"
                required />

            <!-- Descrição -->
            <label for="descricao">Descrição:</label>
            <textarea
                id="descricao"
                name="descricao"
                placeholder="Descreva o estabelecimento"
                required><?php echo htmlspecialchars($descricao); ?></textarea>

            <!-- Valor por Hora -->
            <label for="valor_hora">Valor por Hora (R$):</label>
            <input type="number" id="valor_hora" name="valor_hora" value="<?php echo htmlspecialchars($valor_hora); ?>" placeholder="Valor da Hora" min="0" step="0.01" required />

            <!-- Horários de Funcionamento -->
            <label for="funcionamento">Horários de Funcionamento:</label>
            <div class="horarios-funcionamento">
                <!-- Segunda-feira -->
                <label>
                    <span>Segunda-feira</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_seg"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_seg']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_seg"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_seg']); ?>"
                            class="hora-input" />
                    </div>
                </label>
                <!-- Terça-feira -->
                <label>
                    <span>Terça-feira</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_ter"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_ter']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_ter"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_ter']); ?>"
                            class="hora-input" />
                    </div>
                </label>
                <!-- Quarta-feira -->
                <label>
                    <span>Quarta-feira</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_qua"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_qua']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_qua"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_qua']); ?>"
                            class="hora-input" />
                    </div>
                </label>
                <!-- Quinta-feira -->
                <label>
                    <span>Quinta-feira</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_qui"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_qui']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_qui"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_qui']); ?>"
                            class="hora-input" />
                    </div>
                </label>
                <!-- Sexta-feira -->
                <label>
                    <span>Sexta-feira</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_sex"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_sex']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_sex"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_sex']); ?>"
                            class="hora-input" />
                    </div>
                </label>
                <!-- Sábado -->
                <label>
                    <span>Sábado</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_sab"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_sab']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_sab"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_sab']); ?>"
                            class="hora-input" />
                    </div>
                </label>
                <!-- Domingo -->
                <label>
                    <span>Domingo</span>
                    <div class="hora-input-container">
                        <input
                            type="time"
                            name="hora_abertura_dom"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_abertura_dom']); ?>"
                            class="hora-input" />
                        <span>até</span>
                        <input
                            type="time"
                            name="hora_fechamento_dom"
                            value="<?php echo htmlspecialchars($estabelecimento['hora_fechamento_dom']); ?>"
                            class="hora-input" />
                    </div>
                </label>

            </div>
            <input type="submit" class="btn-salvar" value="Salvar Alterações" />

        </form>
    </section>

    <section id="galeria-fotos" class="dashboard-section hidden">
        <form action="config/salvar_imagens.php" method="POST" enctype="multipart/form-data" class="form-upload">
            <h2 class="form-title">Upload de Fotos do Estabelecimento</h2>

            <!-- Foto Principal -->
            <div class="form-group">
                <label for="foto_principal">Foto Principal:</label>
                <input type="file" name="foto_principal" id="foto_principal" accept="image/*" class="custom-file-input">
            </div>

            <!-- Fotos Adicionais -->
            <div class="form-group">
                <label for="foto_adicional_1">Foto Adicional 1:</label>
                <input type="file" name="foto_adicional_1" id="foto_adicional_1" accept="image/*" class="custom-file-input">
            </div>

            <div class="form-group">
                <label for="foto_adicional_2">Foto Adicional 2:</label>
                <input type="file" name="foto_adicional_2" id="foto_adicional_2" accept="image/*" class="custom-file-input">
            </div>

            <div class="form-group">
                <label for="foto_adicional_3">Foto Adicional 3:</label>
                <input type="file" name="foto_adicional_3" id="foto_adicional_3" accept="image/*" class="custom-file-input">
            </div>

            <div class="form-group">
                <label for="foto_adicional_4">Foto Adicional 4:</label>
                <input type="file" name="foto_adicional_4" id="foto_adicional_4" accept="image/*" class="custom-file-input">
            </div>

            <button type="submit" class="btn-salvar">Salvar Fotos</button>
        </form>
    </section>


    <!-- Opções de Segurança -->
    <section id="opcoes-seguranca" class="dashboard-section hidden">
        <h2>Opções de Segurança</h2>

        <!-- Alteração de Email -->
        <form class="alteracao-email" id="form-alterar-email" method="POST" action="config/processar_seguranca.php">
            <label for="email-atual">Email Atual:</label>
            <input type="email" id="email-atual" name="email-atual" required />

            <label for="novo-email">Novo Email:</label>
            <input type="email" id="novo-email" name="novo-email" required />

            <input type="submit" class="btn-seguranca" value="Salvar" />
        </form>

        <!-- Alteração de Senha -->
        <form class="alteracao-senha" id="form-alterar-senha" method="POST" action="config/processar_seguranca.php">
            <label for="senha-atual">Senha Atual:</label>
            <input type="password" id="senha-atual" name="senha-atual" required />

            <label for="nova-senha">Nova Senha:</label>
            <input type="password" id="nova-senha" name="nova-senha" required />

            <label for="confirmar-senha">Confirmar Nova Senha:</label>
            <input type="password" id="confirmar-senha" name="confirmar-senha" required />

            <input type="submit" class="btn-seguranca" value="Salvar" />
        </form>

        </div>
    </section>

</main>

<!-- Tela de loading fullscreen -->
<div class="fullscreen-loading" id="fullscreen-loading">
    <div class="loading-spinner"></div>
</div>


<script src="js/loading.js"></script>
<script src="js/update.js"></script>

<?php

include_once("templates/footer.php");

?>