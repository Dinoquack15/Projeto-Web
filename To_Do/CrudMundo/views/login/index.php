<?php
require_once '../../config/conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se já está logado, manda direto pro dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: ../../index.php');
    exit;
}

$erro = '';
$sucesso = $_GET['registrado'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: ../../index.php');
            exit;
        } else {
            $erro = 'E-mail ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - CRUD Mundo</title>
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
    </header>

    <div class="container login-box">
        <h2>Entrar</h2>
        <br>

        <?php if ($erro): ?>
            <div class="mensagem-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="mensagem-sucesso">Cadastro realizado com sucesso! Faça login abaixo.</div>
        <?php endif; ?>

        <form method="POST">
            <label>E-mail</label>
            <input type="email" name="email" required autofocus>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <br><br>
            <button type="submit" class="btn">Entrar</button>
        </form>

        <p style="margin-top: 15px;">
            Não tem conta? <a href="registrar.php">Cadastre-se</a>
        </p>
    </div>
</body>
</html>
