<?php
require_once '../../config/conexao.php';
require_once '../../config/auth.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM paises WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Erro ao excluir país. Verifique se existem cidades vinculadas a ele.");
    }
}

header('Location: index.php');
exit;
?>