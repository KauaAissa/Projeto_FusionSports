<?php

include_once("templates/header-index.php");
include_once 'config/config.php';

if (isset($_GET['city'])) {
	$city = $_GET['city'];


	$stmt = $pdo->prepare("SELECT * FROM estabelecimentos WHERE cidade LIKE :city");
	$stmt->execute(['city' => '%' . $city . '%']);
	$resultados = $stmt->fetchAll();


	if ($resultados) {
		echo "<h3>Quadras encontradas em: " . htmlspecialchars($city) . "</h3>";
		echo "<ul>";
		foreach ($resultados as $estabelecimento) {
			echo "<li>" . htmlspecialchars($estabelecimento['nome_estabelecimento']) . "</li>";
		}
		echo "</ul>";
	} else {
		echo "<h3>Nenhuma quadra encontrada em: " . htmlspecialchars($city) . "</h3>";
	}
}
?>

<main>
	<div class="main-banner">
		<img
			src="img/mainbannerPhoto.png"
			alt="FusionSports"
			id="main-banner-img" />

		<section class="search-city">
			<h2>Busque quadras próximas a você!</h2>
			<div class="search-city-form">
				<form method="GET" action="resultados.php">
					<input type="text" name="city" id="city" placeholder="Digite sua Cidade" autocomplete="off" />
					<input type="submit" value="Buscar" />
				</form>
			</div>
		</section>
	</div>

	<section class="services-container section" id="passos">
		<h3>Passo a Passo</h3>
		<h2>Como Agendar?</h2>
		<ul>
			<a href="#">
				<h3>1° Passo</h3>
				<img
					src="img/world.png"
					alt="Imagem procurando por algum lugar"
					class="first-image" />
				<h3>Procure sua Cidade</h3>
				<p>
					Encontre a quadra que desejada ou que atenda as suas
					necessidades!
				</p>
			</a>
			<li>
				<h3>2° Passo</h3>
				<img
					src="img/calendar.png"
					alt="Imagem realizando uma agenda"
					class="second-image" />
				<h3>Agende seu horário</h3>
				<p>Agende e pague diretamente no site, bem mais prático né?</p>
			</li>

			<li>
				<h3>3° Passo</h3>
				<img src="img/field.png" alt="" class="third-image" />
				<h3>Gerencie</h3>
				<p>
					Forme ou sorteie os times, selecione posições, conecte os
					jogares e tenha suporte do estabelecimento, tudo em um só lugar!
				</p>
			</li>
		</ul>
	</section>

	<section class="functions section" id="funcoes">
		<h3>Funcionalidades</h3>
		<h2>Quais são as Funcionalidades do FusionSports?</h2>

		<div class="function-cards">
			<div class="function-card">
				<div class="function-content">
					<h5>Pagamento Online</h5>
					<p>
						Aqui no FusionSports você pode pagar sem sair do lugar! Você
						pode optar por pagar no local ou pagar online ao agendar.
					</p>
				</div>
				<img
					src="img/payment.png"
					alt="Pagamento Online"
					class="function-icon" />
			</div>
			<div class="function-card">
				<div class="function-content">
					<h5>Chame os amigos!</h5>
					<p>
						Chega de bagunça no compartilhamento da partida! Aqui nós
						conectamos todos os jogadores, arrumar uma partida nunca foi
						tão fácil né?
					</p>
				</div>
				<img
					src="img/qrcode.png"
					alt="Organização de Partida"
					class="function-icon" />
			</div>
			<div class="function-card">
				<div class="function-content">
					<h5>Sorteio dos times</h5>
					<p>
						Chega de perder tempo na hora de praticar seu esporte! Sorteie
						os times com antecêdencia e evite confusões.
					</p>
				</div>
				<img
					src="img/match.png"
					alt="Sorteio dos Times"
					class="function-icon" />
			</div>
			<div class="function-card">
				<div class="function-content">
					<h5>Conheça novas quadras</h5>
					<p>
						Aqui rápidamente você encontra todas as quadras parceiras de
						diversos esportes perto de você!
					</p>
				</div>
				<img
					src="img/search.png"
					alt="Sorteio dos Times"
					class="function-icon" />
			</div>
		</div>
	</section>

	<section class="faq-container" id="faq">
		<h3>FAQ</h3>
		<h2>Perguntas Frequentes</h2>
		<div class="faq">
			<div class="faq-item">
				<button class="faq-question">
					Preciso criar conta para usar o site?
				</button>
				<div class="faq-answer">
					<p>
						Você pode utilizar o FusionSports sem ter uma conta, apenar é
						necessário criar quando você desejar agendar algum horário!
					</p>
				</div>
			</div>
			<div class="faq-item">
				<button class="faq-question">
					Quais formas de pagamento são aceitas?
				</button>
				<div class="faq-answer">
					<p>
						Aceitamos PIX, boletos, cartões de crédito e débito, além de
						pagamento em dinheiro nas quadras parceiras.
					</p>
				</div>
			</div>
			<div class="faq-item">
				<button class="faq-question">
					Como posso cancelar um agendamento?
				</button>
				<div class="faq-answer">
					<p>
						O cancelamento pode ser feito diretamente pelo site na área de
						gerenciar agendamentos. Verifique as políticas de cancelamento
						da quadra escolhida.
					</p>
				</div>
			</div>
			<div class="faq-item">
				<button class="faq-question">
					Quero registrar minha quadra, como faço e quais seriam as
					Vantagens?
				</button>
				<div class="faq-answer">
					<p>
						Para registrar sua quadra e conferir suas vantagens clique no
						botão de cadastre sua quadra no canto superior direito do
						site.
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="contact-container section" id="contato">
		<h3>Contato</h3>
		<h2>Mande uma mensagem</h2>
		<p>Envie sua mensagem caso tenha alguma dúvida ou interesse.</p>

		<?php
		if (isset($_GET['status'])) {
			if ($_GET['status'] == 'sucesso') {
			} elseif ($_GET['status'] == 'erro') {
				echo '<p style="color: red;">Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.</p>';
			}
		}
		?>

		<form action="config/processar_contato.php" method="POST" id="contact-form">
			<input type="text" name="name" id="name" placeholder="Seu nome" required />
			<input type="email" name="email" id="email" placeholder="Digite seu email" required />
			<textarea name="message" id="message" placeholder="Descreva sua dúvida" required></textarea>
			<input type="submit" value="Enviar" />
		</form>
	</section>
</main>



<div class="fullscreen-loading" id="fullscreen-loading">
	<div class="loading-spinner"></div>
</div>

<div id="successModal" class="modal">
	<div class="modal-content">
		<span class="close-button">&times;</span>
		<p>Mensagem enviada com sucesso!</p>
	</div>
</div>

<script src="js/update.js"></script>





<?php

include_once("templates/footer.php")

?>