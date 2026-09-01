<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$sql = "SELECT p.*, c.nome AS continente_nome, g.nome AS governante_nome 
        FROM paises p 
        LEFT JOIN continentes c ON p.id_continente = c.id 
        LEFT JOIN governantes g ON p.id_governante = g.id";
$stmt = $pdo->query($sql);
$paises = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Países</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/main.js" defer></script>
</head>
<body>
    <header>
        <h1>Gerenciamento de Países</h1>
        <nav>
            <a href="../../index.php">Dashboard</a>
            <a href="../continentes/index.php">Continentes</a>
            <a href="../governantes/index.php">Governantes</a>
            <a href="index.php">Países</a>
            <a href="../cidades/index.php">Cidades</a>
            <a href="../usuarios/index.php">Usuários</a>
            <a href="../logs/index.php">Logs</a>
                    <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="../login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <a href="salvar.php" class="btn">+ Novo País</a>
            <input type="text" id="busca" placeholder="🔍 Pesquisa dinâmica..." style="width: 250px;">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Continente</th>
                    <th>População</th>
                    <th>Governante</th>
                    <th>Moeda</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paises as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['continente_nome'] ?? 'N/A') ?></td>
                    <td><?= number_format($p['populacao'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['governante_nome'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($p['moeda']) ?></td>
                    <td class="actions">
                        <a href="salvar.php?id=<?= $p['id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="excluir.php?id=<?= $p['id'] ?>" class="btn btn-danger" onclick="confirmarExclusao(event, '<?= $p['nome'] ?>')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>