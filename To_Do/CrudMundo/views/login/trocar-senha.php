<?php
require_once '../../config/conexao.php';
require_once '../../config/log.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Só chega aqui quem acabou de fazer login corretamente e está marcado
// como "primeiro acesso". Sem essa sessão temporária, volta pro login.
if (empty($_SESSION['troca_senha_usuario_id'])) {
    header('Location: index.php');
    exit;
}

$idUsuario = $_SESSION['troca_senha_usuario_id'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if (strlen($novaSenha) < 6) {
        $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
    } elseif ($novaSenha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "UPDATE usuarios SET senha = ?, primeiro_acesso = 0, tentativas_falhas = 0 WHERE id = ?"
        );
        $stmt->execute([$hash, $usuario['id']]);
        registrarLog($pdo, $usuario['id'], $usuario['email'], 'TROCA_SENHA');

        // Agora sim, libera o acesso completo ao sistema
        unset($_SESSION['troca_senha_usuario_id']);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header('Location: ../../index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Trocar Senha - CRUD Mundo</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .login-box {
            max-width: 380px;
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
        .mensagem-aviso {
            background: #fef9e7;
            color: #b7860b;
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
        <h2>Troca de senha obrigatória</h2>
        <br>

        <div class="mensagem-aviso">
            Este é o seu primeiro acesso, <?= htmlspecialchars($usuario['nome']) ?>. Por segurança,
            defina uma nova senha antes de continuar.
        </div>

        <?php if ($erro): ?>
            <div class="mensagem-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nova senha</label>
            <input type="password" name="nova_senha" required minlength="6" autofocus>

            <label>Confirmar nova senha</label>
            <input type="password" name="confirmar_senha" required minlength="6">

            <br><br>
            <button type="submit" class="btn">Salvar nova senha e entrar</button>
        </form>
    </div>
</body>
</html>
