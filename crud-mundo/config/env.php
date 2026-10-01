<?php
/**
 * Carrega as variáveis de um arquivo .env para o ambiente do PHP.
 * Variáveis já definidas no sistema têm prioridade sobre as do arquivo.
 *
 * @param string $caminhoArquivo caminho completo do arquivo .env
 */
function carregarEnv(string $caminhoArquivo): void
{
    if (!is_readable($caminhoArquivo)) {
        return;
    }

    $linhas = file($caminhoArquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        if ($linha === '' || $linha[0] === '#' || strpos($linha, '=') === false) {
            continue;
        }

        [$chave, $valor] = explode('=', $linha, 2);
        $chave = trim($chave);
        $valor = trim($valor, " \t\"'");

        if ($chave !== '' && getenv($chave) === false) {
            putenv("$chave=$valor");
        }
    }
}
