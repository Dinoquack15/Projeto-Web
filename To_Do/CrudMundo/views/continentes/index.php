<?php
require_once '../../config/conexao.php';

$stmt = $pdo->query("SELECT * FROM continentes");
$continentes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Continentes</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/main.js" defer></script>
</head>
<body>
    <header>
        <h1>Gerenciamento de Continentes</h1>
        <nav>
            <a href="../../index.php">Dashboard</a>
            <a href="index.php">Continentes</a>
            <a href="../governantes/index.php">Governantes</a>
            <a href="../paises/index.php">Países</a>
            <a href="../cidades/index.php">Cidades</a>
        </nav>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <a href="salvar.php" class="btn">+ Novo Continente</a>
            <input type="text" id="busca" placeholder="🔍 Pesquisar..." style="width: 250px;">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>População</th>
                    <th>Área (km²)</th>
                    <th>Total de Países</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($continentes as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= number_format($c['populacao'] ?? 0, 0, ',', '.') ?></td>
                    <td><?= number_format($c['area_km2'] ?? 0, 2, ',', '.') ?></td>
                    <td><?= $c['total_paises'] ?? 0 ?></td>
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