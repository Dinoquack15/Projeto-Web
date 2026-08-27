<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$stmt = $pdo->query("SELECT * FROM governantes");
$governantes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Governantes</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/main.js" defer></script>
</head>
<body>
    <header>
        <h1>Gerenciamento de Governantes</h1>
        <nav>
            <a href="../../index.php">Dashboard</a>
            <a href="../continentes/index.php">Continentes</a>
            <a href="index.php">Governantes</a>
            <a href="../paises/index.php">Países</a>
            <a href="../cidades/index.php">Cidades</a>
                    <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="../login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <a href="salvar.php" class="btn">+ Novo Governante</a>
            <input type="text" id="busca" placeholder="🔍 Pesquisar..." style="width: 250px;">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Partido</th>
                    <th>Idade</th>
                    <th>Início Mandato</th>
                    <th>Fim Mandato</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($governantes as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['nome']) ?></td>
                    <td><?= htmlspecialchars($g['partido_politico']) ?></td>
                    <td><?= $g['idade'] ?></td>
                    <td><?= $g['data_inicio_mandato'] ?></td>
                    <td><?= $g['data_fim_mandato'] ?></td>
                    <td class="actions">
                        <a href="salvar.php?id=<?= $g['id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="excluir.php?id=<?= $g['id'] ?>" class="btn btn-danger" onclick="confirmarExclusao(event, '<?= $g['nome'] ?>')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>