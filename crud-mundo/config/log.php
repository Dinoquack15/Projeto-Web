<?php
/**
 * Grava um evento na tabela LOGS.
 * Usado para auditar tentativas de login, bloqueios, trocas de senha, etc.
 *
 * @param PDO         $pdo
 * @param int|null    $idUsuario   id do usuário (null se o e-mail nem existe na base)
 * @param string      $email       e-mail digitado na tentativa
 * @param string      $acao        LOGIN_SUCESSO | LOGIN_FALHA | CONTA_BLOQUEADA | TENTATIVA_BLOQUEADO | TROCA_SENHA | LOGOUT | DESBLOQUEIO_MANUAL
 */
function registrarLog(PDO $pdo, ?int $idUsuario, string $email, string $acao): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';

    $stmt = $pdo->prepare(
        "INSERT INTO logs (id_usuario, email_tentativa, acao, ip_address) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$idUsuario, $email, $acao, $ip]);
}
