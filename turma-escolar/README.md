# Turma Escolar - Análise Estatística

## Sobre o projeto

Aplicação web que recebe os dados de uma turma (nome dos alunos e três notas por aluno) e gera um relatório com a média e a situação de cada estudante, além de estatísticas gerais da turma.

## Funcionalidades

- Geração dinâmica dos campos de cadastro conforme a quantidade de alunos informada
- Cálculo da média de cada aluno (duas provas e um trabalho)
- Classificação do aluno em Aprovado (média a partir de 7), Recuperação (a partir de 5) ou Reprovado
- Estatísticas da turma: média geral, maior e menor média, quantidade de aprovados, em recuperação e reprovados, percentual de aprovação e soma total das notas
- Mensagem automática sobre o desempenho geral da turma

## Tecnologias utilizadas

- PHP
- JavaScript
- HTML
- CSS
- Git
- GitHub

## Estrutura do projeto

```
turma-escolar/
├── css/style.css   # Estilos da página
├── js/script.js    # Geração dos campos dos alunos
└── index.php       # Formulário e cálculo do relatório
```

## Como executar

### Requisitos

- PHP 8
- Git

### Passos

1. Clone o repositório e acesse a pasta do projeto:

   ```bash
   git clone https://github.com/<seu-usuario>/projetos-programacao-web.git
   cd projetos-programacao-web/turma-escolar
   ```

2. Inicie o servidor embutido do PHP:

   ```bash
   php -S localhost:8000
   ```

3. Acesse `http://localhost:8000` no navegador.

## Autor

Eduardo Rodrigo Souza Alves
