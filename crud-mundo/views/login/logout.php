<?php
require_once '../../config/conexao.php';
require_once '../../config/log.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['usuario_id'])) {
    registrarLog($pdo, $_SESSION['usuario_id'], $_SESSION['usuario_nome'] ?? '', 'LOGOUT');
}

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;
