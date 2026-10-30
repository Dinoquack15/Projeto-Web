<?php
/**
 * Chave de acesso à API Maps JavaScript do Google.
 *
 * A chave fica no arquivo .env (não versionado), na variável GOOGLE_MAPS_KEY.
 * Use o .env.example como modelo.
 * Atenção: a chave do Maps JavaScript fica visível no navegador. Por isso,
 * restrinja-a aos endereços HTTP autorizados no Google Cloud Console.
 */
require_once __DIR__ . '/env.php';

carregarEnv(dirname(__DIR__) . '/.env');

$googleMapsKey = getenv('GOOGLE_MAPS_KEY') ?: '';
