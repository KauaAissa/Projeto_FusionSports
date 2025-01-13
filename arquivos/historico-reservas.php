<?php
include_once("templates/header-gerenciar-estabelecimento.php");
include_once 'config/config.php';


session_start();
if (!isset($_SESSION['id_dono'])) {
	header("Location: estabelecimento-login.php");
	exit();
}


$id_dono = $_SESSION['id_dono'];


$stmtEstabelecimento = $pdo->prepare("SELECT id_estabelecimento, nome_estabelecimento FROM estabelecimentos WHERE id_dono = :id_dono");
$stmtEstabelecimento->execute(['id_dono' => $id_dono]);
$estabelecimento = $stmtEstabelecimento->fetch(PDO::FETCH_ASSOC);
$id_estabelecimento = $estabelecimento['id_estabelecimento'];
$nome_estabelecimento = $estabelecimento['nome_estabelecimento'];

$stmtReservas = $pdo->prepare("SELECT reservas.id_reserva, clientes.nome AS nome_cliente, reservas.data_reserva, reservas.horario_inicio, reservas.horario_fim, reservas.valor_pago, reservas.status 
                               FROM reservas 
                               JOIN clientes ON reservas.id_cliente = clientes.id_cliente 
                               WHERE reservas.id_estabelecimento = :id_estabelecimento AND reservas.status = 'finalizada'");
$stmtReservas->execute(['id_estabelecimento' => $id_estabelecimento]);
$reservas = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
	<section class="historico-reservas">
		<h2>Histórico de Reservas de: <span class="nome-estabelecimento"> <?php echo htmlspecialchars($nome_estabelecimento); ?></span></h2>
		<div class="table-wrapper">
			<table>
				<thead>
					<tr>
						<th>ID Reserva</th>
						<th>Cliente</th>
						<th>Data</th>
						<th>Horário</th>
						<th>Valor Pago</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($reservas as $reserva) : ?>
						<tr>
							<td><?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
							<td><?php echo htmlspecialchars($reserva['nome_cliente']); ?></td>
							<td><?php echo htmlspecialchars($reserva['data_reserva']); ?></td>
							<td><?php echo htmlspecialchars($reserva['horario_inicio'] . ' - ' . $reserva['horario_fim']); ?></td>
							<td>R$ <?php echo number_format($reserva['valor_pago'], 2, ',', '.'); ?></td>
							<td><?php echo htmlspecialchars($reserva['status']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</section>
</main>


<div class="fullscreen-loading" id="fullscreen-loading">
	<div class="loading-spinner"></div>
</div>

<script src="js/loading.js"></script>
<script src="js/update.js"></script>

<?php include_once("templates/footer.php"); ?>