<?php
require_once 'config/conexao.php';
require_once 'config/auth.php';

$totalCidades = $pdo->query("SELECT COUNT(*) FROM cidades")->fetchColumn();
$totalPaises = $pdo->query("SELECT COUNT(*) FROM paises")->fetchColumn();

$cidadeMaisPopulosa = $pdo->query("SELECT nome, populacao FROM cidades ORDER BY populacao DESC LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD Mundo - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
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
                    <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="views/login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <h2>Estatísticas do Sistema</h2>
        <br>
        <p><strong>Total de Países Cadastrados:</strong> <?= $totalPaises ?></p>
        <p><strong>Total de Cidades Cadastradas:</strong> <?= $totalCidades ?></p>
        <p><strong>Cidade Mais Populosa:</strong> 
            <?= $cidadeMaisPopulosa ? $cidadeMaisPopulosa['nome'] . " (" . number_format($cidadeMaisPopulosa['populacao'], 0, ',', '.') . " hab)" : 'Nenhuma cadastrada' ?>
        </p>
    </div>
</body>
</html>