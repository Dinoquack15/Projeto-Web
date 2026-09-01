<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';
require_once '../../config/log.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $stmt = $pdo->prepare("UPDATE usuarios SET bloqueado = 0, tentativas_falhas = 0 WHERE id = ?");
        $stmt->execute([$id]);
        registrarLog($pdo, (int) $id, $usuario['email'], 'DESBLOQUEIO_MANUAL');
    }
}

header('Location: index.php');
exit;
