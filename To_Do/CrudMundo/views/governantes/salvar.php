<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$id = $_GET['id'] ?? null;
$gov = ['nome'=>'', 'partido_politico'=>'', 'data_nascimento'=>'', 'idade'=>'', 'data_inicio_mandato'=>'', 'data_fim_mandato'=>''];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM governantes WHERE id = ?");
    $stmt->execute([$id]);
    $gov = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $partido = $_POST['partido_politico'];
    $data_nasc = $_POST['data_nascimento'] ?: null;
    $idade = $_POST['idade'];
    $inicio = $_POST['data_inicio_mandato'] ?: null;
    $fim = $_POST['data_fim_mandato'] ?: null;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE governantes SET nome=?, partido_politico=?, data_nascimento=?, idade=?, data_inicio_mandato=?, data_fim_mandato=? WHERE id=?");
        $stmt->execute([$nome, $partido, $data_nasc, $idade, $inicio, $fim, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO governantes (nome, partido_politico, data_nascimento, idade, data_inicio_mandato, data_fim_mandato) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $partido, $data_nasc, $idade, $inicio, $fim]);
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Salvar Governante</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin: 40px auto;">
        <h2><?= $id ? 'Editar' : 'Cadastrar' ?> Governante</h2>
        <form method="POST">
            <div>
                <label>Nome *</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($gov['nome']) ?>" required>
            </div>
            <div>
                <label>Partido Político</label>
                <input type="text" name="partido_politico" value="<?= htmlspecialchars($gov['partido_politico']) ?>">
            </div>
            <div>
                <label>Data Nascimento</label>
                <input type="date" name="data_nascimento" value="<?= $gov['data_nascimento'] ?>">
            </div>
            <div>
                <label>Idade</label>
                <input type="number" name="idade" value="<?= $gov['idade'] ?>">
            </div>
            <div>
                <label>Início do Mandato</label>
                <input type="date" name="data_inicio_mandato" value="<?= $gov['data_inicio_mandato'] ?>">
            </div>
            <div>
                <label>Final do Mandato</label>
                <input type="date" name="data_fim_mandato" value="<?= $gov['data_fim_mandato'] ?>">
            </div>
            <div class="full-width">
                <button type="submit">Salvar</button>
                <a href="index.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>