<?php

include_once("templates/header-resultados.php");
include_once 'config/config.php';


$limite_por_pagina = 5;


$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite_por_pagina;

?>

<main>
    <section class="dashboard">
        <h2>Resultados da Busca</h2>

        <!-- Barra de pesquisa -->
        <div class="search-bar">
            <form method="GET" action="resultados.php">
                <input type="text" name="city" id="city" placeholder="Pesquisar por outra Cidade" autocomplete="off"
                    value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>" />
                <button type="submit" class="btn-pesquisar">Pesquisar</button>
            </form>
            <div class="suggestions-container"></div> <!-- Contêiner para as sugestões -->
        </div>

        <!-- Mensagem ou resultados -->
        <?php

        if (isset($_GET['city'])) {
            $city = $_GET['city'];


            $stmt = $pdo->prepare("SELECT * FROM estabelecimentos WHERE cidade LIKE :city LIMIT :limite OFFSET :offset");
            $stmt->bindValue(':city', '%' . $city . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limite', (int)$limite_por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll();


            $stmt_total = $pdo->prepare("SELECT COUNT(*) FROM estabelecimentos WHERE cidade LIKE :city");
            $stmt_total->execute(['city' => '%' . $city . '%']);
            $total_resultados = $stmt_total->fetchColumn();


            if ($resultados) {
                echo "<h2>Resultados da busca para: " . htmlspecialchars($city) . "</h2>";
                echo '<div class="quadra-lista">';

                foreach ($resultados as $estabelecimento) {

                    $fotoPrincipal = $estabelecimento['foto_principal'];
                    $idDono = $estabelecimento['id_dono'];
                    $imagemEstabelecimento = !empty($fotoPrincipal) ? 'uploads/estabelecimentos/' . $idDono . '/' . basename($fotoPrincipal) : 'img/fLogo.png';

                    echo '
                <div class="quadra-card">
                    <div class="quadra-imagem">
                        <img src="' . htmlspecialchars($imagemEstabelecimento) . '" alt="Foto do Estabelecimento" />
                    </div>
                    <div class="quadra-info">
                        <h3>' . htmlspecialchars($estabelecimento['nome_estabelecimento']) . '</h3>
                        <p>Descrição: ' . htmlspecialchars($estabelecimento['descricao']) . '</p>
                        <p>Localização: ' . htmlspecialchars($estabelecimento['cidade']) . '</p>
                        <a href="visao-geral.php?id_dono=' . $idDono . '" class="btn-detalhes">Ver detalhes</a>
                    </div>
                </div>';
                }

                echo '</div>';


                $total_paginas = ceil($total_resultados / $limite_por_pagina);

                echo '<div class="pagination">';
                if ($pagina_atual > 1) {
                    echo '<a href="?city=' . urlencode($city) . '&pagina=' . ($pagina_atual - 1) . '">Anterior</a>';
                }

                for ($i = 1; $i <= $total_paginas; $i++) {
                    echo '<a href="?city=' . urlencode($city) . '&pagina=' . $i . '">' . $i . '</a>';
                }

                if ($pagina_atual < $total_paginas) {
                    echo '<a href="?city=' . urlencode($city) . '&pagina=' . ($pagina_atual + 1) . '">Próximo</a>';
                }
                echo '</div>';
            } else {
                echo "<h2>Nenhum estabelecimento encontrado em: " . htmlspecialchars($city) . "</h2>";
            }
        } else {
            echo "<h2>Nenhuma cidade foi selecionada.</h2>";
        }
        ?>
    </section>
</main>

<script src="js/resultados.js"></script>
<script src="js/update.js"></script>


<div class="fullscreen-loading" id="fullscreen-loading">
    <div class="loading-spinner"></div>
</div>

<?php

include_once("templates/footer.php");

?>