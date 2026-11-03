<?php
require_once 'config/auth.php';
require_once 'config/google_maps.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Mundo - Mapa de Países</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        #mapa {
            width: 100%;
            height: 520px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .filtros {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filtros select {
            width: auto;
            min-width: 220px;
        }
        #mensagem {
            margin-top: 10px;
            color: #555;
        }
        .popup-pais img {
            width: 120px;
            border: 1px solid #ddd;
            display: block;
            margin-bottom: 6px;
        }
        .popup-pais p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>🌍 Gerenciador CRUD Mundo</h1>
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
        <h2>Mapa de Países</h2>
        <p>Os dados são obtidos em tempo real pela API REST Countries (restcountries.com).</p>

        <?php if ($googleMapsKey === ''): ?>
            <p><strong>Chave da API do Google Maps não configurada.</strong>
            Defina GOOGLE_MAPS_KEY no arquivo .env.</p>
        <?php else: ?>
            <div class="filtros" style="margin-top: 15px;">
                <label for="filtro-regiao" style="margin: 0;">Filtrar por região:</label>
                <select id="filtro-regiao">
                    <option value="">Todas as regiões</option>
                </select>
            </div>

            <div id="mensagem">Carregando países...</div>
            <div id="mapa"></div>
        <?php endif; ?>
    </div>

    <?php if ($googleMapsKey !== ''): ?>
    <script>
        const URL_API = 'https://restcountries.com/v3.1/all?fields=name,cca2,capital,region,subregion,population,latlng,flags';

        let mapa;
        let janelaInfo;
        let marcadores = [];
        let paises = [];

        const selectRegiao = document.getElementById('filtro-regiao');
        const mensagem = document.getElementById('mensagem');

        // Escapa texto vindo da API antes de montar o HTML do popup.
        function escapar(texto) {
            return String(texto)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function montarConteudoPopup(pais) {
            const capital = Array.isArray(pais.capital) && pais.capital.length
                ? pais.capital.join(', ')
                : 'Não informada';
            const populacao = Number(pais.population || 0).toLocaleString('pt-BR');
            const bandeira = pais.flags && pais.flags.png
                ? `<img src="${escapar(pais.flags.png)}" alt="Bandeira de ${escapar(pais.name.common)}">`
                : '';

            return `
                <div class="popup-pais">
                    ${bandeira}
                    <strong>${escapar(pais.name.common)}</strong>
                    <p>Capital: ${escapar(capital)}</p>
                    <p>Região: ${escapar(pais.region || 'N/A')}${pais.subregion ? ' - ' + escapar(pais.subregion) : ''}</p>
                    <p>População: ${populacao} hab.</p>
                </div>`;
        }

        function preencherRegioes() {
            const regioes = [...new Set(paises.map(p => p.region).filter(Boolean))].sort();
            regioes.forEach(regiao => {
                const opcao = document.createElement('option');
                opcao.value = regiao;
                opcao.textContent = regiao;
                selectRegiao.appendChild(opcao);
            });
        }

        function limparMarcadores() {
            marcadores.forEach(m => m.setMap(null));
            marcadores = [];
        }

        function desenharPaises() {
            limparMarcadores();
            janelaInfo.close();

            const regiaoEscolhida = selectRegiao.value;
            const filtrados = paises.filter(p => !regiaoEscolhida || p.region === regiaoEscolhida);

            const limites = new google.maps.LatLngBounds();

            filtrados.forEach(pais => {
                const posicao = { lat: pais.latlng[0], lng: pais.latlng[1] };

                const marcador = new google.maps.Marker({
                    position: posicao,
                    map: mapa,
                    title: pais.name.common
                });

                marcador.addListener('click', () => {
                    janelaInfo.setContent(montarConteudoPopup(pais));
                    janelaInfo.open({ anchor: marcador, map: mapa });
                });

                marcadores.push(marcador);
                limites.extend(posicao);
            });

            if (filtrados.length > 0 && regiaoEscolhida) {
                mapa.fitBounds(limites);
            } else if (!regiaoEscolhida) {
                mapa.setCenter({ lat: -15, lng: -50 });
                mapa.setZoom(2);
            }

            mensagem.textContent = `${filtrados.length} países exibidos.`;
        }

        async function carregarPaises() {
            try {
                const resposta = await fetch(URL_API);
                if (!resposta.ok) {
                    throw new Error('HTTP ' + resposta.status);
                }

                const dados = await resposta.json();
                // Alguns territórios não possuem coordenadas; eles são ignorados no mapa.
                paises = dados.filter(p => Array.isArray(p.latlng) && p.latlng.length === 2);

                preencherRegioes();
                desenharPaises();
            } catch (erro) {
                mensagem.textContent = 'Não foi possível carregar os dados da API REST Countries (' + erro.message + ').';
            }
        }

        // Chamada pelo Google Maps quando a API estiver pronta (callback=iniciarMapaPaises).
        function iniciarMapaPaises() {
            mapa = new google.maps.Map(document.getElementById('mapa'), {
                zoom: 2,
                center: { lat: -15, lng: -50 }
            });

            janelaInfo = new google.maps.InfoWindow();

            selectRegiao.addEventListener('change', desenharPaises);

            carregarPaises();
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?= urlencode($googleMapsKey) ?>&callback=iniciarMapaPaises&loading=async"
        async>
    </script>
    <?php endif; ?>
</body>
</html>
