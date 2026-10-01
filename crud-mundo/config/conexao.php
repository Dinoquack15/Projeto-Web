<?php
/**
 * Conexão com o banco de dados via PDO.
 *
 * As credenciais não ficam no código: são lidas do arquivo .env
 * (não versionado) ou das variáveis de ambiente do sistema.
 * Use o arquivo .env.example como modelo.
 */
require_once __DIR__ . '/env.php';

carregarEnv(dirname(__DIR__) . '/.env');

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'bd_mundo';
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

if ($user === false) {
    die('Configuração do banco ausente. Copie o arquivo .env.example para .env e preencha as credenciais.');
}

$pass = ($pass === false) ? '' : $pass;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // O detalhe técnico vai para o log do servidor, não para a tela.
    error_log('Erro de conexão com o banco: ' . $e->getMessage());
    die('Não foi possível conectar ao banco de dados. Verifique as configurações do arquivo .env.');
}
