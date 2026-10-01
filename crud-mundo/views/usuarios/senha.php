<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';
require_once '../../config/log.php';

$idUsuario = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_destroy();
    header('Location: ../login/index.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual     = $_POST['senha_atual'] ?? '';
    $novaSenha      = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if ($senhaAtual === '' || $novaSenha === '' || $confirmarSenha === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (!password_verify($senhaAtual, $usuario['senha'])) {
        $erro = 'A senha atual informada está incorreta.';
    } elseif (strlen($novaSenha) < 6) {
        $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
    } elseif ($novaSenha !== $confirmarSenha) {
        $erro = 'A nova senha e a confirmação não coincidem.';
    } elseif ($novaSenha === $senhaAtual) {
        $erro = 'A nova senha deve ser diferente da senha atual.';
    } else {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->execute([$hash, $usuario['id']]);

        registrarLog($pdo, $usuario['id'], $usuario['email'], 'TROCA_SENHA');

        $sucesso = 'Senha alterada com sucesso!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Manutenção de Senha - CRUD Mundo</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/main.js" defer></script>
    <style>
        .senha-box {
            max-width: 420px;
            margin: 0 auto;
        }
        .senha-box form {
            display: block;
        }
        .senha-box label {
            margin-top: 12px;
        }
        .mensagem-erro {
            background: #fdecea;
            color: #e74c3c;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .mensagem-sucesso {
            background: #eafaf1;
            color: #27ae60;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>🌍 Gerenciador CRUD Mundo</h1>
        <nav>
            <a href="../../index.php">Dashboard</a>
            <a href="../continentes/index.php">Continentes</a>
            <a href="../governantes/index.php">Governantes</a>
            <a href="../paises/index.php">Países</a>
            <a href="../cidades/index.php">Cidades</a>
            <a href="index.php">Usuários</a>
            <a href="../logs/index.php">Logs</a>
            <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="senha.php">Minha Senha</a> | <a href="../login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container senha-box">
        <h2>Manutenção de Senha</h2>
        <br>

        <?php if ($erro): ?>
            <div class="mensagem-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="mensagem-sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Senha atual</label>
            <input type="password" name="senha_atual" required autofocus>

            <label>Nova senha</label>
            <input type="password" name="nova_senha" required minlength="6">

            <label>Confirmar nova senha</label>
            <input type="password" name="confirmar_senha" required minlength="6">

            <br><br>
            <button type="submit" class="btn">Salvar nova senha</button>
        </form>
    </div>
</body>
</html>
