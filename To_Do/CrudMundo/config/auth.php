<?php
/**
 * Arquivo de proteção de páginas.
 * Basta dar require_once neste arquivo no topo de qualquer página
 * que só pode ser acessada por usuários logados.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    // Descobre a partir de qual pasta este auth.php foi chamado,
    // para montar o caminho correto até a tela de login,
    // não importa a profundidade da pasta (raiz ou views/xxx).
    $chamador = debug_backtrace()[0]['file'];
    $raizProjeto = dirname(__DIR__); // pasta CrudMundo
    $pastaChamador = str_replace('\\', '/', dirname($chamador));
    $caminhoRelativo = trim(substr($pastaChamador, strlen($raizProjeto)), '/');

    $niveis = $caminhoRelativo === '' ? 0 : substr_count($caminhoRelativo, '/') + 1;
    $prefixo = str_repeat('../', $niveis);

    header("Location: {$prefixo}views/login/index.php");
    exit;
}
