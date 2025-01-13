<?php
session_start();
include_once("templates/header-usuario-logado.php");
include_once 'config/config.php';


if (isset($_GET['id_dono']) && !empty($_GET['id_dono'])) {
    $id_dono = $_GET['id_dono'];
    $_SESSION['id_dono'] = $id_dono;
} elseif (isset($_SESSION['id_dono']) && !empty($_SESSION['id_dono'])) {
    $id_dono = $_SESSION['id_dono'];
} else {
    echo "ID do estabelecimento não foi fornecido ou é inválido.";
    exit();
}


$stmt = $pdo->prepare("SELECT nome_estabelecimento, descricao, valor_hora, foto_principal, foto_adicional_1, foto_adicional_2, foto_adicional_3, foto_adicional_4, 
    hora_abertura_seg, hora_fechamento_seg, hora_abertura_ter, hora_fechamento_ter, hora_abertura_qua, hora_fechamento_qua,
    hora_abertura_qui, hora_fechamento_qui, hora_abertura_sex, hora_fechamento_sex, hora_abertura_sab, hora_fechamento_sab,
    hora_abertura_dom, hora_fechamento_dom
    FROM estabelecimentos WHERE id_dono = :id_dono");

$stmt->execute(['id_dono' => $id_dono]);
$estabelecimento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estabelecimento) {
    echo "Estabelecimento não encontrado no banco de dados.";
    exit();
}


$uploadDir = 'uploads/estabelecimentos/' . $id_dono . '/';


$fotoPrincipal = !empty($estabelecimento['foto_principal']) ? $uploadDir . basename($estabelecimento['foto_principal']) : 'img/fLogo.png';

// Verifica e define o caminho das fotos adicionais
$fotosAdicionais = [
    !empty($estabelecimento['foto_adicional_1']) ? $uploadDir . basename($estabelecimento['foto_adicional_1']) : 'img/fLogo.png',
    !empty($estabelecimento['foto_adicional_2']) ? $uploadDir . basename($estabelecimento['foto_adicional_2']) : 'img/fLogo.png',
    !empty($estabelecimento['foto_adicional_3']) ? $uploadDir . basename($estabelecimento['foto_adicional_3']) : 'img/fLogo.png',
    !empty($estabelecimento['foto_adicional_4']) ? $uploadDir . basename($estabelecimento['foto_adicional_4']) : 'img/fLogo.png',
];

// Horários de funcionamento
$horarios = [
    'domingo' => [$estabelecimento['hora_abertura_dom'], $estabelecimento['hora_fechamento_dom']],
    'segunda' => [$estabelecimento['hora_abertura_seg'], $estabelecimento['hora_fechamento_seg']],
    'terca' => [$estabelecimento['hora_abertura_ter'], $estabelecimento['hora_fechamento_ter']],
    'quarta' => [$estabelecimento['hora_abertura_qua'], $estabelecimento['hora_fechamento_qua']],
    'quinta' => [$estabelecimento['hora_abertura_qui'], $estabelecimento['hora_fechamento_qui']],
    'sexta' => [$estabelecimento['hora_abertura_sex'], $estabelecimento['hora_fechamento_sex']],
    'sabado' => [$estabelecimento['hora_abertura_sab'], $estabelecimento['hora_fechamento_sab']]
];
?>

<script>
    // Passar os horários de funcionamento para o JavaScript
    const horariosFuncionamento = <?php echo json_encode($horarios); ?>;
</script>

<main>
    <section class="dashboard">
        <h2 class="estabelecimento-nome"><?php echo htmlspecialchars($estabelecimento['nome_estabelecimento']); ?></h2>

        <!-- Imagem Principal -->
        <div class="imagem-principal">
            <img src="<?php echo htmlspecialchars($fotoPrincipal); ?>" alt="Imagem Principal do Estabelecimento" />
        </div>

        <!-- Galeria de Imagens Adicionais -->
        <div class="galeria-imagens">
            <?php foreach ($fotosAdicionais as $foto): ?>
                <img src="<?php echo htmlspecialchars($foto); ?>" alt="Imagem Adicional" />
            <?php endforeach; ?>
        </div>

        <!-- Descrição do Estabelecimento -->
        <div class="descricao-estabelecimento">
            <p><?php echo htmlspecialchars($estabelecimento['descricao']); ?></p>
            <p class="valor-hora">Valor por hora: R$<?php echo number_format($estabelecimento['valor_hora'], 2, ',', '.'); ?></p>
        </div>


    </section>

    <div class="calendario-container">
        <h2>Disponibilidade</h2>
        <h3>Veja as melhores opções</h3>
    </div>


    <section class="calendario-container section" id="contato">
        <!-- Calendário de Disponibilidade -->
        <div class="calendario-disponibilidade">

            <!-- Controles para navegação entre semanas -->
            <div class="semana-controle">
                <button id="semana-anterior">&lt; Semana Anterior</button>
                <span id="semana-atual">Semana Atual</span>
                <button id="semana-proxima">Próxima Semana &gt;</button>
            </div>

            <!-- Tabela para exibir a semana e horários de funcionamento -->
            <div class="tabela-calendario">
                <?php foreach ($horarios as $dia => $horario): ?>
                    <div class="dia-semana" id="<?php echo $dia; ?>">
                        <h4><?php echo ucfirst($dia); ?></h4>
                        <div class="horarios">
                            <span>Carregando horários...</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<!-- Modal de Pagamento -->
<div id="modal-pagamento" class="modal">
    <div class="modal-content">
        <!-- "X" para fechar o modal -->
        <span class="close-modal">&times;</span>

        <h2>Escolha o método de pagamento</h2>

        <!-- Formulário de pagamento -->
        <form id="form-pagamento">
            <div class="metodo-pagamento">
                <label>
                    <input type="radio" name="metodo" value="dinheiro" checked>
                    Pagamento em Dinheiro
                </label>
                <label>
                    <input type="radio" name="metodo" value="paypal">
                    Pagamento via PayPal
                </label>
            </div>


            <input type="hidden" name="id_estabelecimento" id="id_estabelecimento" value="<?php echo htmlspecialchars($id_dono); ?>">
            <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo htmlspecialchars($_SESSION['id_cliente'] ?? ''); ?>">

            <input type="hidden" name="data_reserva" id="data_reserva">
            <input type="hidden" name="horario_inicio" id="horario_inicio">
            <input type="hidden" name="horario_fim" id="horario_fim">

            <button type="submit" class="btn-confirmar">Confirmar Pagamento</button>
        </form>
    </div>
</div>

</div>
</div>

<script src="js/finalizar-agendamento.js"></script>
<script src="js/update.js"></script>


<div class="fullscreen-loading" id="fullscreen-loading">
    <div class="loading-spinner"></div>
</div>

<?php
include_once("templates/footer.php");
?>