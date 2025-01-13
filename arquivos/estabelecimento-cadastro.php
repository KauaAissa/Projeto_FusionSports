<?php

include_once("templates/header-estabelecimento-cadastro-login.php");

?>

<div id="main-container">
	<!-- Formulário para contato -->
	<section id="form-container" class="section">
		<form id="contact-form" action="config/processar_cadastro_estabelecimento.php" method="POST">
			<!-- Seção 1: Informações Pessoais -->
			<div class="form-step active">
				<h1 class="form-heading">Informações pessoais</h1>
				<label for="name">Nome Completo:</label>
				<input
					type="text"
					name="name"
					placeholder="Digite seu nome"
					required />
				<label for="email">E-mail:</label>
				<input
					type="email"
					name="email"
					placeholder="Digite seu e-mail"
					required />
				<label for="phone">Telefone:</label>
				<input
					type="text"
					name="phone"
					placeholder="Digite seu telefone"
					required />
				<label for="password">Senha:</label>
				<input
					type="password"
					name="password"
					placeholder="Digite sua senha"
					required />
				<label for="confirm_password">Confirme sua Senha:</label>
				<input
					type="password"
					name="confirm_password"
					placeholder="Confirme sua senha"
					required />
				<button type="button" class="next-btn">Próximo</button>
				<p class="redirect-text">
					Já tem uma conta?
					<a href="estabelecimento-login.php" class="redirect-link">Clique aqui para fazer login</a>.
				</p>
			</div>

			<!-- Seção 2: Informações do Estabelecimento -->
			<div class="form-step">
				<h1 class="form-heading">Informações do Estabelecimento</h1>
				<label for="establishment_name">Nome do estabelecimento:</label>
				<input
					type="text"
					name="establishment_name"
					placeholder="Digite o nome do Estabelecimento"
					required />
				<label for="establishment_address">Cidade do Estabelecimento:</label>
				<input
					type="text"
					name="establishment_address"
					placeholder="Digite a cidade do Estabelecimento"
					required />
				<!-- Campo para Horários de Funcionamento -->
				<label for="horariosFuncionamento">Horários de funcionamento:</label>
				<div id="horariosFuncionamento">
					<label for="inicio">Início:</label>
					<input
						type="time"
						id="inicio"
						name="inicioFuncionamento"
						required />
					<label for="termino">Término:</label>
					<input
						type="time"
						id="termino"
						name="terminoFuncionamento"
						required />
				</div>
				<label for="valor_hora">Valor por Hora:</label>
				<input
					type="number"
					name="valor_hora"
					placeholder="Digite o valor cobrado por hora"
					step="0.01"
					min="0"
					required />
				<label for="description">Descrição do Estabelecimento:</label>
				<input
					type="text"
					name="description"
					placeholder="Descreva seu Estabelecimento" />
				<div class="form-navigation">
					<button type="button" class="prev-btn">Anterior</button>
					<input type="submit" value="Cadastrar" />
				</div>
			</div>
		</form>
	</section>

	<!-- Informações de endereço -->
	<div id="address-container">
		<div class="fade"></div>
	</div>
</div>

<!-- Tela de loading fullscreen -->
<div class="fullscreen-loading" id="fullscreen-loading">
	<div class="loading-spinner"></div>
</div>

<script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
<script src="js/auth-script.js"></script>
<script src="js/update.js"></script>

<?php

include_once("templates/footer.php");

?>
