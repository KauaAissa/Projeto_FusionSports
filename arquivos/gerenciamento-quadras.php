<?php

include_once("templates/header-gerenciar-estabelecimento.php");
include_once 'config/config.php';


session_start();
if (!isset($_SESSION['id_dono'])) {
	header("Location: estabelecimento-login.php");
	exit();
}


$id_dono = $_SESSION['id_dono'];


$stmt = $pdo->prepare("SELECT donos.nome AS nome_dono, estabelecimentos.nome_estabelecimento 
                       FROM donos 
                       JOIN estabelecimentos ON donos.id_dono = estabelecimentos.id_dono 
                       WHERE donos.id_dono = :id_dono");
$stmt->execute(['id_dono' => $id_dono]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);


$nomeDono = $userInfo['nome_dono'];
$nomeEstabelecimento = $userInfo['nome_estabelecimento'];

?>

<main>
	<section class="dashboard">
		<h2>Bem-vindo, <?php echo htmlspecialchars($nomeDono); ?>!</h2>
		<h3 class="titulo">Gerenciando o estabelecimento: <span class="nome-tela"><?php echo htmlspecialchars($nomeEstabelecimento); ?></span></h3>

		<div class="dashboard-grid">
			<div class="dashboard-card">
				<h3>Histórico de Reservas</h3>
				<p>Ver todas as reservas passadas</p>
				<a href="historico-reservas.php"><button>Ver Detalhes</button></a>
			</div>
			<div class="dashboard-card">
				<h3>Reservas Pendentes</h3>
				<p>Confirmar ou recusar reservas</p>
				<a href="reservas-pendentes.php"><button>Ver Detalhes</button></a>
			</div>
			<div class="dashboard-card">
				<h3>Reservas Ativas</h3>
				<p>Gerenciar as reservas confirmadas</p>
				<a href="reservas-ativas.php"><button>Ver Detalhes</button></a>
			</div>
		</div>
	</section>
</main>


<div class="fullscreen-loading" id="fullscreen-loading">
	<div class="loading-spinner"></div>
</div>

<script src="js/loading.js"></script>
<script src="js/update.js"></script>

<?php
include_once("templates/footer.php");
?>