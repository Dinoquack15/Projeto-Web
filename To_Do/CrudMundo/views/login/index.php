<?php
require_once '../../config/conexao.php';
require_once '../../config/log.php';

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

const LIMITE_TENTATIVAS = 3;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            // E-mail não cadastrado. Não informamos se o e-mail existe ou não,
            // por segurança, mas registramos a tentativa.
            registrarLog($pdo, null, $email, 'LOGIN_FALHA');
            $erro = 'E-mail ou senha inválidos.';

        } elseif ((int) $usuario['bloqueado'] === 1) {
            registrarLog($pdo, $usuario['id'], $email, 'TENTATIVA_BLOQUEADO');
            $erro = 'Sua conta foi bloqueada após 3 tentativas de senha incorreta. Procure um administrador para desbloqueá-la.';

        } elseif (password_verify($senha, $usuario['senha'])) {
            // Login correto: zera o contador de tentativas
            $stmt = $pdo->prepare("UPDATE usuarios SET tentativas_falhas = 0 WHERE id = ?");
            $stmt->execute([$usuario['id']]);
            registrarLog($pdo, $usuario['id'], $email, 'LOGIN_SUCESSO');

            if ((int) $usuario['primeiro_acesso'] === 1) {
                // Ainda não pode acessar o sistema: precisa trocar a senha antes
                $_SESSION['troca_senha_usuario_id'] = $usuario['id'];
                header('Location: trocar-senha.php');
                exit;
            }

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: ../../index.php');
            exit;

        } else {
            // Senha incorreta: incrementa o contador de tentativas consecutivas
            $tentativas = (int) $usuario['tentativas_falhas'] + 1;

            if ($tentativas >= LIMITE_TENTATIVAS) {
                $stmt = $pdo->prepare("UPDATE usuarios SET tentativas_falhas = ?, bloqueado = 1 WHERE id = ?");
                $stmt->execute([$tentativas, $usuario['id']]);
                registrarLog($pdo, $usuario['id'], $email, 'CONTA_BLOQUEADA');
                $erro = 'Senha incorreta. Sua conta foi bloqueada após 3 tentativas consecutivas.';
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET tentativas_falhas = ? WHERE id = ?");
                $stmt->execute([$tentativas, $usuario['id']]);
                registrarLog($pdo, $usuario['id'], $email, 'LOGIN_FALHA');
                $restantes = LIMITE_TENTATIVAS - $tentativas;
                $erro = "Senha incorreta. Você tem mais {$restantes} tentativa(s) antes do bloqueio da conta.";
            }
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
