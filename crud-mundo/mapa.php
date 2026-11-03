<?php
require_once 'config/auth.php';
require_once 'config/google_maps.php';

// Dados da unidade (coordenadas fixas nesta primeira versão).
// Confira a posição exata no Google Maps (clique com o botão direito no local
// e copie as coordenadas) e ajuste os valores abaixo, se necessário.
$unidade = "ETEC Profª Ilza Nascimento Pintus";
$endereco = "Av. Salmão, 570 - Parque Residencial Aquarius - São José dos Campos/SP";
$latitude = -23.1896;
$longitude = -45.8841;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Mundo - Mapa da ETEC</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        #mapa {
            width: 100%;
            height: 480px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .endereco-unidade {
            margin: 5px 0 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Gerenciador CRUD Mundo</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="views/continentes/index.php">Continentes</a>
            <a href="views/governantes/index.php">Governantes</a>
            <a href="views/paises/index.php">Países</a>
            <a href="views/cidades/index.php">Cidades</a>
            <a href="views/usuarios/index.php">Usuários</a>
            <a href="views/logs/index.php">Logs</a>
            <a href="mapa.php">Mapa</a>
            <a href="mapa-paises.php">Mapa de Países</a>
                    <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="views/usuarios/senha.php">Minha Senha</a> | <a href="views/login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <h2>Localização: <?= htmlspecialchars($unidade) ?></h2>
        <p class="endereco-unidade"><?= htmlspecialchars($endereco) ?></p>

        <?php if ($googleMapsKey === ''): ?>
            <p><strong>Chave da API do Google Maps não configurada.</strong>
            Defina GOOGLE_MAPS_KEY no arquivo .env.</p>
        <?php else: ?>
            <div id="mapa"></div>
        <?php endif; ?>
    </div>

    <?php if ($googleMapsKey !== ''): ?>
    <script>
        function iniciarMapa() {
            const localizacao = {
                lat: <?= json_encode($latitude) ?>,
                lng: <?= json_encode($longitude) ?>
            };

            const mapa = new google.maps.Map(
                document.getElementById("mapa"),
                {
                    zoom: 15,
                    center: localizacao
                }
            );

            new google.maps.Marker({
                position: localizacao,
                map: mapa,
                title: <?= json_encode($unidade) ?>
            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?= urlencode($googleMapsKey) ?>&callback=iniciarMapa&loading=async"
        async>
    </script>
    <?php endif; ?>
</body>
</html>
