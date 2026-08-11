<?php
require_once '../../config/conexao.php';

$id = $_GET['id'] ?? null;
$pais = ['nome'=>'', 'id_continente'=>'', 'id_governante'=>'', 'populacao'=>'', 'area_km2'=>'', 'idioma'=>'', 'clima'=>'', 'regime_politico'=>'', 'moeda'=>''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM paises WHERE id = ?");
    $stmt->execute([$id]);
    $pais = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $id_continente = $_POST['id_continente'] ?: null;
    $id_governante = $_POST['id_governante'] ?: null;
    $populacao = $_POST['populacao'];
    $area_km2 = $_POST['area_km2'];
    $idioma = $_POST['idioma'];
    $clima = $_POST['clima'];
    $regime_politico = $_POST['regime_politico'];
    $moeda = $_POST['moeda'];

    if ($id) {
        $sql = "UPDATE paises SET nome=?, id_continente=?, id_governante=?, populacao=?, area_km2=?, idioma=?, clima=?, regime_politico=?, moeda=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $id_continente, $id_governante, $populacao, $area_km2, $idioma, $clima, $regime_politico, $moeda, $id]);
    } else {
        $sql = "INSERT INTO paises (nome, id_continente, id_governante, populacao, area_km2, idioma, clima, regime_politico, moeda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $id_continente, $id_governante, $populacao, $area_km2, $idioma, $clima, $regime_politico, $moeda]);
    }
    header('Location: index.php');
    exit;
}

$continentes = $pdo->query("SELECT id, nome FROM continentes")->fetchAll();
$governantes = $pdo->query("SELECT id, nome FROM governantes")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Salvar País</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin: 40px auto;">
        <h2><?= $id ? 'Editar' : 'Cadastrar' ?> País</h2>
        <form method="POST">
            <div>
                <label>Nome *</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($pais['nome']) ?>" required>
            </div>
            <div>
                <label>Continente</label>
                <select name="id_continente">
                    <option value="">Selecione...</option>
                    <?php foreach ($continentes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $pais['id_continente'] == $c['id'] ? 'selected' : '' ?>><?= $c['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Governante</label>
                <select name="id_governante">
                    <option value="">Selecione...</option>
                    <?php foreach ($governantes as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= $pais['id_governante'] == $g['id'] ? 'selected' : '' ?>><?= $g['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>População</label>
                <input type="number" name="populacao" value="<?= $pais['populacao'] ?>">
            </div>
            <div>
                <label>Área (km²)</label>
                <input type="number" step="0.01" name="area_km2" value="<?= $pais['area_km2'] ?>">
            </div>
            <div>
                <label>Idioma</label>
                <input type="text" name="idioma" value="<?= htmlspecialchars($pais['idioma']) ?>">
            </div>
            <div>
                <label>Clima</label>
                <input type="text" name="clima" value="<?= htmlspecialchars($pais['clima']) ?>">
            </div>
            <div>
                <label>Regime Político</label>
                <input type="text" name="regime_politico" value="<?= htmlspecialchars($pais['regime_politico']) ?>">
            </div>
            <div>
                <label>Moeda</label>
                <input type="text" name="moeda" value="<?= htmlspecialchars($pais['moeda']) ?>">
            </div>
            <div class="full-width">
                <button type="submit">Salvar Registro</button>
                <a href="index.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>