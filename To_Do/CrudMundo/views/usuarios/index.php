<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$usuarios = $pdo->query(
    "SELECT id, nome, email, tentativas_falhas, bloqueado, primeiro_acesso, criado_em
     FROM usuarios
     ORDER BY nome"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/main.js" defer></script>
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
            <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="../login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <h2>Usuários do Sistema</h2>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Tentativas Falhas</th>
                    <th>Status</th>
                    <th>Primeiro Acesso</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= (int) $u['tentativas_falhas'] ?></td>
                    <td>
                        <?php if ((int) $u['bloqueado'] === 1): ?>
                            <span style="color:#e74c3c; font-weight:bold;">🔒 Bloqueado</span>
                        <?php else: ?>
                            <span style="color:#27ae60; font-weight:bold;">✅ Ativo</span>
                        <?php endif; ?>
                    </td>
                    <td><?= (int) $u['primeiro_acesso'] === 1 ? 'Pendente' : 'Concluído' ?></td>
                    <td><?= htmlspecialchars($u['criado_em']) ?></td>
                    <td class="actions">
                        <?php if ((int) $u['bloqueado'] === 1): ?>
                            <a href="desbloquear.php?id=<?= $u['id'] ?>" class="btn btn-warning"
                               onclick="return confirm('Desbloquear o acesso de <?= htmlspecialchars($u['nome']) ?>?')">
                               Desbloquear
                            </a>
                        <?php else: ?>
                            &mdash;
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
