<?php
include_once("templates/header-estabelecimento-cadastro-login.php");

if (isset($_GET['erro']) && $_GET['erro'] == 1) {
	echo "<p style='color: red;'>E-mail ou senha inválidos. Por favor, tente novamente.</p>";
}
?>


<!-- Main container -->
<div id="main-container">
	<!-- Formulário de login -->
	<section id="form-container" class="section">
		<form id="login-form" method="POST" action="config/processar_login_estabelecimento.php">
			<h1 class="form-heading">Login</h1>
			<label for="email">E-mail:</label>
			<input
				type="email"
				name="email"
				placeholder="Digite seu e-mail"
				required />

			<label for="password">Senha:</label>
			<input
				type="password"
				name="password"
				placeholder="Digite sua senha"
				required />

			<input type="submit" value="Login" />

			<p class="redirect-text">
				Não tem uma conta?
				<a href="estabelecimento-cadastro.php" class="redirect-link">Clique aqui para se cadastrar</a>.
			</p>
		</form>
	</section>


	<div id="address-container">
		<div class="fade"></div>
	</div>
</div>


<div class="fullscreen-loading" id="fullscreen-loading">
	<div class="loading-spinner"></div>
</div>

<script src="js/auth-script.js"></script>
<script src="js/update.js"></script>

<?php
include_once("templates/footer.php");
?>