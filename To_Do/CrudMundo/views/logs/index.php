<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$logs = $pdo->query(
    "SELECT l.*, u.nome AS usuario_nome
     FROM logs l
     LEFT JOIN usuarios u ON l.id_usuario = u.id
     ORDER BY l.data_hora DESC
     LIMIT 300"
)->fetchAll();

$rotulosAcao = [
    'LOGIN_SUCESSO'        => ['✅ Login com sucesso', '#27ae60'],
    'LOGIN_FALHA'          => ['⚠️ Senha incorreta', '#f39c12'],
    'CONTA_BLOQUEADA'      => ['🔒 Conta bloqueada (3 tentativas)', '#e74c3c'],
    'TENTATIVA_BLOQUEADO'  => ['🔒 Tentativa em conta bloqueada', '#e74c3c'],
    'TROCA_SENHA'          => ['🔑 Troca de senha (primeiro acesso)', '#2980b9'],
    'DESBLOQUEIO_MANUAL'   => ['🔓 Desbloqueio manual', '#8e44ad'],
    'LOGOUT'               => ['🚪 Logout', '#7f8c8d'],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Logs de Autenticação</title>
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
            <a href="../usuarios/index.php">Usuários</a>
            <a href="index.php">Logs</a>
            <span style="color:#ecf0f1; margin-left:15px;">👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | <a href="../login/logout.php">Sair</a></span>
        </nav>
    </header>

    <div class="container">
        <h2>Logs de Autenticação</h2>
        <p style="color:#666; margin-top:5px;">Últimos 300 eventos, mais recentes primeiro.</p>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <?php
                    $info = $rotulosAcao[$log['acao']] ?? [$log['acao'], '#333'];
                    $nomeExibido = $log['usuario_nome'] ?? $log['email_tentativa'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($log['data_hora']) ?></td>
                    <td><?= htmlspecialchars($nomeExibido ?? 'N/A') ?></td>
                    <td style="color: <?= $info[1] ?>; font-weight: bold;"><?= htmlspecialchars($info[0]) ?></td>
                    <td><?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
