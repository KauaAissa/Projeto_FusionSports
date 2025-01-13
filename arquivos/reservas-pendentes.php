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


$stmtReservas = $pdo->prepare("SELECT reservas.id_reserva, clientes.nome AS nome_cliente, reservas.data_reserva, reservas.horario_inicio, reservas.horario_fim 
                               FROM reservas 
                               JOIN clientes ON reservas.id_cliente = clientes.id_cliente 
                               WHERE reservas.id_estabelecimento = :id_estabelecimento AND reservas.status = 'Pendente'");
$stmtReservas->execute(['id_estabelecimento' => $id_estabelecimento]);
$reservas = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
	<section class="dashboard">
		<h2>Reservas Pendentes de: <span class="nome-estabelecimento"><?php echo htmlspecialchars($nome_estabelecimento); ?></span></h2>


		<?php if (isset($_GET['mensagem'])): ?>
			<p class="mensagem-sucesso"><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
		<?php endif; ?>

		<table>
			<thead>
				<tr>
					<th>ID Reserva</th>
					<th>Cliente</th>
					<th>Data</th>
					<th>Horário</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($reservas as $reserva) : ?>
					<tr>
						<td><?php echo htmlspecialchars($reserva['id_reserva']); ?></td>
						<td><?php echo htmlspecialchars($reserva['nome_cliente']); ?></td>
						<td><?php echo htmlspecialchars($reserva['data_reserva']); ?></td>
						<td><?php echo htmlspecialchars($reserva['horario_inicio'] . ' - ' . $reserva['horario_fim']); ?></td>
						<td>
							<!-- Formulário para processar a ação -->
							<form action="config/processar_reserva.php" method="POST" style="display:inline;">
								<input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
								<button type="submit" name="acao" value="confirmar" class="btn-confirmar">Confirmar</button>
								<button type="submit" name="acao" value="rejeitar" class="btn-rejeitar">Rejeitar</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
</main>


<div class="fullscreen-loading" id="fullscreen-loading">
	<div class="loading-spinner"></div>
</div>

<script src="js/loading.js"></script>
<script src="js/update.js"></script>

<?php include_once("templates/footer.php"); ?>