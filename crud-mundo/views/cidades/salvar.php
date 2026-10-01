<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$id = $_GET['id'] ?? null;
$cid = ['nome'=>'', 'id_pais'=>'', 'id_governante'=>'', 'populacao'=>'', 'area_km2'=>'', 'clima'=>'', 'data_fundacao'=>''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM cidades WHERE id = ?");
    $stmt->execute([$id]);
    $cid = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $id_pais = $_POST['id_pais'];
    $id_governante = $_POST['id_governante'] ?: null;
    $populacao = $_POST['populacao'];
    $area_km2 = $_POST['area_km2'];
    $clima = $_POST['clima'];
    $fundacao = $_POST['data_fundacao'] ?: null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE cidades SET nome=?, id_pais=?, id_governante=?, populacao=?, area_km2=?, clima=?, data_fundacao=? WHERE id=?");
        $stmt->execute([$nome, $id_pais, $id_governante, $populacao, $area_km2, $clima, $fundacao, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cidades (nome, id_pais, id_governante, populacao, area_km2, clima, data_fundacao) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $id_pais, $id_governante, $populacao, $area_km2, $clima, $fundacao]);
    }
    header('Location: index.php');
    exit;
}

$paises = $pdo->query("SELECT id, nome FROM paises")->fetchAll();
$governantes = $pdo->query("SELECT id, nome FROM governantes")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Salvar Cidade</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin: 40px auto;">
        <h2><?= $id ? 'Editar' : 'Cadastrar' ?> Cidade</h2>
        <form method="POST">
            <div>
                <label>Nome *</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($cid['nome']) ?>" required>
            </div>
            <div>
                <label>País *</label>
                <select name="id_pais" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($paises as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $cid['id_pais'] == $p['id'] ? 'selected' : '' ?>><?= $p['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Governante</label>
                <select name="id_governante">
                    <option value="">Selecione...</option>
                    <?php foreach ($governantes as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= $cid['id_governante'] == $g['id'] ? 'selected' : '' ?>><?= $g['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>População</label>
                <input type="number" name="populacao" value="<?= $cid['populacao'] ?>">
            </div>
            <div>
                <label>Área (km²)</label>
                <input type="number" step="0.01" name="area_km2" value="<?= $cid['area_km2'] ?>">
            </div>
            <div>
                <label>Clima</label>
                <input type="text" name="clima" value="<?= htmlspecialchars($cid['clima']) ?>">
            </div>
            <div>
                <label>Data de Fundação</label>
                <input type="date" name="data_fundacao" value="<?= $cid['data_fundacao'] ?>">
            </div>
            <div class="full-width">
                <button type="submit">Salvar</button>
                <a href="index.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>