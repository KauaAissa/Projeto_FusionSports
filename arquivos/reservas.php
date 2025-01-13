<?php
session_start();
include_once("templates/header-usuario-logado.php");
include_once 'config/config.php';

$idCliente = $_SESSION['id_cliente'];

// Define o diretório base para as imagens
$baseDir = 'uploads/estabelecimentos/';

// Defina o número de reservas por página
$reservasPorPagina = 3;

// Obter o número da página atual para reservas ativas e histórico
$paginaAtivas = isset($_GET['paginaAtivas']) ? (int)$_GET['paginaAtivas'] : 1;
$paginaHistorico = isset($_GET['paginaHistorico']) ? (int)$_GET['paginaHistorico'] : 1;

// Calcular o offset
$offsetAtivas = ($paginaAtivas - 1) * $reservasPorPagina;
$offsetHistorico = ($paginaHistorico - 1) * $reservasPorPagina;

try {

    $stmtAtivas = $pdo->prepare("
        SELECT reservas.id_reserva, reservas.data_reserva, reservas.horario_inicio, reservas.horario_fim, 
               reservas.status, estabelecimentos.nome_estabelecimento, estabelecimentos.foto_principal, estabelecimentos.id_dono
        FROM reservas
        INNER JOIN estabelecimentos ON reservas.id_estabelecimento = estabelecimentos.id_estabelecimento
        WHERE reservas.id_cliente = :id_cliente AND (reservas.status = 'pendente' OR reservas.status = 'confirmada')
        ORDER BY reservas.data_reserva, reservas.horario_inicio
        LIMIT :offset, :limit
    ");
    $stmtAtivas->bindValue(':id_cliente', $idCliente, PDO::PARAM_INT);
    $stmtAtivas->bindValue(':offset', $offsetAtivas, PDO::PARAM_INT);
    $stmtAtivas->bindValue(':limit', $reservasPorPagina, PDO::PARAM_INT);
    $stmtAtivas->execute();
    $reservasAtivas = $stmtAtivas->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para histórico de reservas
    $stmtHistorico = $pdo->prepare("
        SELECT reservas.id_reserva, reservas.data_reserva, reservas.horario_inicio, reservas.horario_fim, 
               reservas.status, estabelecimentos.nome_estabelecimento, estabelecimentos.foto_principal, estabelecimentos.id_dono
        FROM reservas
        INNER JOIN estabelecimentos ON reservas.id_estabelecimento = estabelecimentos.id_estabelecimento
        WHERE reservas.id_cliente = :id_cliente AND reservas.status = 'finalizada'
        ORDER BY reservas.data_reserva DESC
        LIMIT :offset, :limit
    ");
    $stmtHistorico->bindValue(':id_cliente', $idCliente, PDO::PARAM_INT);
    $stmtHistorico->bindValue(':offset', $offsetHistorico, PDO::PARAM_INT);
    $stmtHistorico->bindValue(':limit', $reservasPorPagina, PDO::PARAM_INT);
    $stmtHistorico->execute();
    $reservasHistorico = $stmtHistorico->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar reservas: " . $e->getMessage();
    exit;
}
?>


<main>
    <section class="dashboard">
        <div class="tabs">
            <button class="tab-btn active" data-target="#reservas-ativas">
                Reservas Ativas
            </button>
            <button class="tab-btn" data-target="#historico-reservas">
                Histórico de Reservas
            </button>
        </div>

        <!-- Reservas Ativas -->
        <div class="tab-content active" id="reservas-ativas">
            <h2 class="h2color">Reservas Ativas</h2>
            <div class="reservas-lista">
                <?php if (!empty($reservasAtivas)): ?>
                    <?php foreach ($reservasAtivas as $reserva): ?>
                        <?php

                        $foto = !empty($reserva['foto_principal'])
                            ? $baseDir . $reserva['id_dono'] . '/' . basename($reserva['foto_principal'])
                            : 'img/fLogo.png';
                        ?>
                        <div class="reserva-item">
                            <div class="reserva-imagem">
                                <img src="<?php echo htmlspecialchars($foto); ?>" alt="Imagem do Estabelecimento" />
                            </div>
                            <div class="reserva-info">
                                <h3><?php echo htmlspecialchars($reserva['nome_estabelecimento']); ?></h3>
                                <p>Data: <?php echo date('d/m/Y', strtotime($reserva['data_reserva'])); ?></p>
                                <p>Horário: <?php echo date('H:i', strtotime($reserva['horario_inicio'])); ?> - <?php echo date('H:i', strtotime($reserva['horario_fim'])); ?></p>
                                <p>
                                    Status:
                                    <span class="status-<?php echo htmlspecialchars($reserva['status']); ?>">
                                        <?php echo ucfirst($reserva['status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Você não possui reservas ativas no momento.</p>
                <?php endif; ?>
            </div>
            <div class="paginacao">
                <a href="?paginaAtivas=<?php echo max(1, $paginaAtivas - 1); ?>"
                    class="<?php echo $paginaAtivas == 1 ? 'disabled' : ''; ?>">
                    Anterior
                </a>
                <a href="?paginaAtivas=<?php echo $paginaAtivas + 1; ?>">Próximo</a>
            </div>
        </div>

        <div class="tab-content" id="historico-reservas">
            <h2 class="h2color">Histórico de Reservas</h2>
            <div class="historico-lista">
                <?php if (!empty($reservasHistorico)): ?>
                    <?php foreach ($reservasHistorico as $reserva): ?>
                        <?php

                        $foto = !empty($reserva['foto_principal'])
                            ? $baseDir . $reserva['id_dono'] . '/' . basename($reserva['foto_principal'])
                            : 'img/fLogo.png';
                        ?>
                        <div class="historico-card">
                            <div class="historico-imagem">
                                <img src="<?php echo htmlspecialchars($foto); ?>" alt="Imagem do Estabelecimento" />
                            </div>
                            <div class="historico-info">
                                <h3><?php echo htmlspecialchars($reserva['nome_estabelecimento']); ?></h3>
                                <p>Data: <?php echo date('d/m/Y', strtotime($reserva['data_reserva'])); ?></p>
                                <p>
                                    Status:
                                    <span class="status-<?php echo htmlspecialchars($reserva['status']); ?>">
                                        <?php echo ucfirst($reserva['status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Você não possui histórico de reservas no momento.</p>
                <?php endif; ?>
            </div>
            <div class="paginacao">
                <a href="?paginaHistorico=<?php echo max(1, $paginaHistorico - 1); ?>"
                    class="<?php echo $paginaHistorico == 1 ? 'disabled' : ''; ?>">
                    Anterior
                </a>
                <a href="?paginaHistorico=<?php echo $paginaHistorico + 1; ?>">Próximo</a>
            </div>
        </div>

    </section>
</main>

<script src="js/reservas.js"></script>
<script src="js/update.js"></script>

<div class="fullscreen-loading" id="fullscreen-loading">
    <div class="loading-spinner"></div>
</div>

<?php include_once("templates/footer.php"); ?>