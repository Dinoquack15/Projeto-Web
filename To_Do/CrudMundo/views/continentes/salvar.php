<?php
require_once '../../config/conexao.php';

$id = $_GET['id'] ?? null;
$continente = ['nome' => '', 'populacao' => '', 'area_km2' => '', 'total_paises' => ''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM continentes WHERE id = ?");
    $stmt->execute([$id]);
    $continente = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $populacao = $_POST['populacao'];
    $area_km2 = $_POST['area_km2'];
    $total_paises = $_POST['total_paises'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE continentes SET nome=?, populacao=?, area_km2=?, total_paises=? WHERE id=?");
        $stmt->execute([$nome, $populacao, $area_km2, $total_paises, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO continentes (nome, populacao, area_km2, total_paises) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $populacao, $area_km2, $total_paises]);
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Salvar Continente</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin: 40px auto;">
        <h2><?= $id ? 'Editar' : 'Cadastrar' ?> Continente</h2>
        <form method="POST">
            <div>
                <label>Nome *</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($continente['nome']) ?>" required>
            </div>
            <div>
                <label>População</label>
                <input type="number" name="populacao" value="<?= $continente['populacao'] ?>">
            </div>
            <div>
                <label>Área (km²)</label>
                <input type="number" step="0.01" name="area_km2" value="<?= $continente['area_km2'] ?>">
            </div>
            <div>
                <label>Total de Países</label>
                <input type="number" name="total_paises" value="<?= $continente['total_paises'] ?>">
            </div>
            <div class="full-width">
                <button type="submit">Salvar</button>
                <a href="index.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>