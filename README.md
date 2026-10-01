# Projetos de Programação Web

## Sobre o projeto

Coleção de aplicações web desenvolvidas com HTML, CSS, JavaScript, PHP e MySQL. O repositório reúne desde páginas estáticas e interações no navegador até um sistema completo de gerenciamento de dados geográficos com autenticação de usuários, controle de tentativas de login e registro de logs.

Cada aplicação fica em sua própria pasta, com README próprio explicando o objetivo, as funcionalidades e como executar.

## Projetos

| Pasta | Descrição |
|-------|-----------|
| [`crud-mundo`](crud-mundo/) | Sistema de gerenciamento de continentes, países, cidades, governantes e usuários, com login e logs de auditoria (PHP, PDO e MySQL) |
| [`turma-escolar`](turma-escolar/) | Aplicação que recebe as notas de uma turma e calcula médias, situação de cada aluno e estatísticas gerais (PHP e JavaScript) |
| [`carrinho-compras`](carrinho-compras/) | Carrinho de compras com lista de produtos, filtro por faixa de preço e total atualizado (JavaScript) |
| [`gerenciador-tarefas`](gerenciador-tarefas/) | Lista de tarefas com data, conclusão e remoção de itens (HTML e JavaScript) |
| [`site-pessoal`](site-pessoal/) | Site pessoal com três páginas: início, informações pessoais e vida acadêmica (HTML) |

## Funcionalidades

- Cadastro, listagem, edição e exclusão de registros (CRUD) com banco de dados MySQL
- Autenticação de usuários com senha criptografada, bloqueio após 3 tentativas incorretas e troca de senha
- Registro de logs das ações de autenticação
- Cálculo de médias e estatísticas de uma turma
- Carrinho de compras com filtro de produtos e persistência no navegador
- Gerenciamento de tarefas com validação de entrada
- Navegação entre as páginas de um site estático

Os detalhes de cada aplicação estão no README da respectiva pasta.

## Tecnologias utilizadas

- HTML
- CSS
- JavaScript
- PHP
- PDO
- MySQL
- Git
- GitHub

## Estrutura do projeto

```
projetos-programacao-web/
├── crud-mundo/            # Sistema CRUD com login (PHP + MySQL)
│   ├── config/            # Conexão com o banco, autenticação e logs
│   ├── css/
│   ├── database/          # Script SQL de criação do banco
│   ├── js/
│   └── views/             # Telas organizadas por módulo
├── turma-escolar/         # Análise estatística de turma
├── carrinho-compras/      # Carrinho de compras
├── gerenciador-tarefas/   # Lista de tarefas
├── site-pessoal/          # Site pessoal (HTML)
├── .gitignore
├── LICENSE
└── README.md
```

## Como executar

### Requisitos

- Git
- Navegador web
- PHP 8 com a extensão `pdo_mysql` (somente para `crud-mundo` e `turma-escolar`)
- MySQL ou MariaDB (somente para `crud-mundo`)

### Passos

1. Clone o repositório:

   ```bash
   git clone https://github.com/<seu-usuario>/projetos-programacao-web.git
   cd projetos-programacao-web
   ```

2. Escolha o projeto e siga as instruções do README da pasta correspondente.

Projetos feitos apenas com HTML e JavaScript (`carrinho-compras`, `gerenciador-tarefas` e `site-pessoal`) podem ser abertos diretamente no navegador, abrindo o arquivo `index.html` (ou `index.htm`) da pasta.

## Autor

Eduardo Rodrigo Souza Alves

## Licença

Distribuído sob a licença MIT. Veja o arquivo [LICENSE](LICENSE).
