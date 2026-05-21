<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Sistema de Análise Estatística</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <h1>Sistema Web de Análise Estatística</h1>
        <form method="POST">
            <label>Nome da Turma:</label>
            <input type="text" name="turma" required>
            <br><br>
            <label>Quantidade de Alunos:</label>
            <input type="number" id="qtd" min="1" required>
            <button type="button" onclick="gerarCampos()"> Gerar Campos </button> <!-- Executa o JS para criar os campos da quantidade de alunos -->
            <div id="camposAlunos"></div>
            <button type="submit" name="calcular">
                Calcular
            </button>
        </form>
        <?php
            if (isset($_POST['calcular'])) { // Caso o botão seja pressionado executa o codigo
                //Recebe os dados
                $turma = $_POST['turma'];
                $nomes = $_POST['nome'];
                $nota1 = $_POST['nota1'];
                $nota2 = $_POST['nota2'];
                $trabalho = $_POST['trabalho'];

                $totalAlunos = count($nomes);

                $somaMedias = 0;
                $maiorMedia = 0;
                $menorMedia = 10;

                $aprovados = 0;
                $recuperacao = 0;
                $reprovados = 0;

                $somaTotalNotas = 0;

                echo "<h2>Relatório da Turma: $turma</h2>";

                echo "
                <table>
                    <tr>
                        <th>Aluno</th>
                        <th>P1</th>
                        <th>P2</th>
                        <th>Trabalho</th>
                        <th>Média</th>
                        <th>Raiz da Soma</th>
                        <th>Diferença</th>
                        <th>Situação</th>
                    </tr>
                "; // Tabela dos resultados

                for ($i = 0; $i < $totalAlunos; $i++) { // Calculos

                    $media = ($nota1[$i] + $nota2[$i] + $trabalho[$i]) / 3;

                    $raiz = sqrt(
                        $nota1[$i] +
                            $nota2[$i] +
                            $trabalho[$i]
                    ); // sqrt = Raiz Quadrada

                    $maiorNota = max(
                        $nota1[$i],
                        $nota2[$i],
                        $trabalho[$i]
                    ); // Max mostra o maior valor

                    $menorNota = min(
                        $nota1[$i],
                        $nota2[$i],
                        $trabalho[$i]
                    ); // Min mostra o menor valor

                    $diferenca = abs($maiorNota - $menorNota); // abs retorna o valor absoluto (Positivo) da diferença

                    // Situação
                    if ($media >= 7) {

                        $situacao = "Aprovado";
                        $classe = "aprovado";
                        $aprovados++;
                    } elseif ($media >= 5) {

                        $situacao = "Recuperação";
                        $classe = "recuperacao";
                        $recuperacao++;
                    } else {

                        $situacao = "Reprovado";
                        $classe = "reprovado";
                        $reprovados++;
                    }

                    // Estatísticas gerais
                    $somaMedias += $media; 

                    if ($media > $maiorMedia) {
                        $maiorMedia = $media;
                    }

                    if ($media < $menorMedia) {
                        $menorMedia = $media;
                    }

                    $somaTotalNotas +=
                        $nota1[$i] +
                        $nota2[$i] +
                        $trabalho[$i];

                    echo "
                        <tr>
                            <td>{$nomes[$i]}</td>
                            <td>{$nota1[$i]}</td>
                            <td>{$nota2[$i]}</td>
                            <td>{$trabalho[$i]}</td>
                            <td>" . number_format($media, 2, ",", ".") . "</td> 
                            <td>" . number_format($raiz, 2, ",", ".") . "</td>
                            <td>" . number_format($diferenca, 2, ",", ".") . "</td>
                            <td class='$classe'>$situacao</td>
                        </tr>
                    ";
                }

                echo "</table>";

                // Relatório geral
                $mediaTurma = $somaMedias / $totalAlunos;

                $percentual =
                    ($aprovados / $totalAlunos) * 100;

                echo "<h2>Estatísticas da Turma</h2>";

                echo "
                    <p><strong>Média Geral:</strong>
                    " . number_format($mediaTurma, 2, ",", ".") . "</p>

                    <p><strong>Maior Média:</strong>
                    " . number_format($maiorMedia, 2, ",", ".") . "</p>

                    <p><strong>Menor Média:</strong>
                    " . number_format($menorMedia, 2, ",", ".") . "</p>

                    <p><strong>Aprovados:</strong>
                    $aprovados</p>

                    <p><strong>Recuperação:</strong>
                    $recuperacao</p>

                    <p><strong>Reprovados:</strong>
                    $reprovados</p>

                    <p><strong>Percentual de Aprovação:</strong>
                    " . number_format($percentual, 2, ",", ".") . "%</p>

                    <p><strong>Soma Total das Notas:</strong>
                    " . number_format($somaTotalNotas, 2, ",", ".") . "</p>
                ";

                // Mensagem automática
                echo "<h2>Desempenho Geral</h2>";

                if ($percentual >= 70) {

                    echo "<p class='aprovado'>
                    Excelente desempenho da turma!
                    </p>";
                } 
                elseif ($percentual >= 50) {
                    echo "<p class='recuperacao'>
                    A turma apresentou desempenho razoável.
                    </p>";
                }
                    else {
                    echo "<p class='reprovado'>
                    A turma precisa melhorar o desempenho.
                    </p>";
                }
            }
        ?>
    </div>
</body>
</html>