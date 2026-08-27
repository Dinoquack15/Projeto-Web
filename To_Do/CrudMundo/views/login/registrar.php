<?php
require_once '../../config/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erro = 'Já existe uma conta com esse e-mail.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $hash]);

            header('Location: index.php?registrado=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - CRUD Mundo</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .login-box {
            max-width: 360px;
            margin: 60px auto;
        }
        .login-box form {
            display: block;
        }
        .login-box label {
            margin-top: 12px;
        }
        .mensagem-erro {
            background: #fdecea;
            color: #e74c3c;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>🌍 Gerenciador CRUD Mundo</h1>
    </header>

    <div class="container login-box">
        <h2>Criar conta</h2>
        <br>

        <?php if ($erro): ?>
            <div class="mensagem-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required autofocus>

            <label>E-mail</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label>Senha</label>
            <input type="password" name="senha" required minlength="6">

            <label>Confirmar senha</label>
            <input type="password" name="confirmar_senha" required minlength="6">

            <br><br>
            <button type="submit" class="btn">Cadastrar</button>
        </form>

        <p style="margin-top: 15px;">
            Já tem conta? <a href="index.php">Fazer login</a>
        </p>
    </div>
</body>
</html>
