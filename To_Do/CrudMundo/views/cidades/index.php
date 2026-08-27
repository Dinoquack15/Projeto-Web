<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$sql = "SELECT cid.*, p.nome AS pais_nome, g.nome AS governante_nome 
        FROM cidades cid 
        LEFT JOIN paises p ON cid.id_pais = p.id 
        LEFT JOIN governantes g ON cid.id_governante = g.id";
$cidades = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Cidades</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/main.js" defer></script>
</head>
<body>
    <header>
        <h1>Gerenciamento de Cidades</h1>
        <nav>
            <a href="../../index.php">Dashboard</a>
            <a href="../continentes/index.php">Continentes</a>
            <a href="../governantes/index.php">Governantes</a>
            <a href="../paises/index.php">Países</a>
            <a href="index.php">Cidades</a>
                    <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="../login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <a href="salvar.php" class="btn">+ Nova Cidade</a>
            <input type="text" id="busca" placeholder="🔍 Pesquisar..." style="width: 250px;">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>País</th>
                    <th>Governante</th>
                    <th>População</th>
                    <th>Fundação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cidades as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['pais_nome'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($c['governante_nome'] ?? 'N/A') ?></td>
                    <td><?= number_format($c['populacao'] ?? 0, 0, ',', '.') ?></td>
                    <td><?= $c['data_fundacao'] ?></td>
                    <td class="actions">
                        <a href="salvar.php?id=<?= $c['id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="excluir.php?id=<?= $c['id'] ?>" class="btn btn-danger" onclick="confirmarExclusao(event, '<?= $c['nome'] ?>')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>