<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
        maximum-scale="1,"
        user-scalable="no" />
    <title>FusionSports</title>
    <!-- Icones -->
    <link
        rel="shortcut icon"
        href="img/fLogo_128x128.png"
        type="image/x-icon" />
    <link rel="stylesheet" href="css/finalizar-agendamento.css" />
    <link rel="stylesheet" href="css/reservas.css">
    <link
        rel="preload"
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap"
        as="style"
        onload="this.onload=null;this.rel='stylesheet'" />
    <noscript>
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    </noscript>
    <link
        rel="preload"
        href="https://fonts.googleapis.com/css2?family=Exo+2&display=swap"
        as="style"
        onload="this.onload=null;this.rel='stylesheet'" />
    <noscript>
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Exo+2&display=swap" />
    </noscript>
</head>

<body>
    <div class="navbar-container" id="navbar">
        <nav>
            <a class="text-name" href="index.php">
                <img src="img/fLogo_128x128.png" alt="FusionSports" class="logo" />
                <span>usion Sports</span>
            </a>
            <div class="navbar-items">
                <a class="btn-reservas" href="reservas.php">Minhas Reservas</a>
                <span class="user-name">
                    <?php
                    if (isset($_SESSION['nome'])) {
                        echo "Bem-vindo, " . htmlspecialchars($_SESSION['nome']);
                    }
                    ?>
                </span>
            </div>
        </nav>
    </div>
</body>

</html>