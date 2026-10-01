# Carrinho de Compras

## Sobre o projeto

Aplicação web de um pequeno mercado: exibe uma lista de produtos com preços, permite filtrar por faixa de preço e montar um carrinho com o total da compra atualizado em tempo real.

## Funcionalidades

- Listagem de produtos com preço formatado em reais
- Filtro de produtos: todos, até R$ 50 ou acima de R$ 50
- Adição de produtos ao carrinho, somando a quantidade quando o item já existe
- Remoção de itens, diminuindo a quantidade ou retirando o produto do carrinho
- Cálculo do total da compra
- Carrinho mantido no navegador (`localStorage`), preservado ao recarregar a página

## Tecnologias utilizadas

- HTML
- CSS
- JavaScript
- Git
- GitHub

## Estrutura do projeto

```
carrinho-compras/
├── css/style.css   # Estilos da página
├── js/script.js    # Produtos, filtro e lógica do carrinho
└── index.html      # Página principal
```

## Como executar

1. Clone o repositório:

   ```bash
   git clone https://github.com/<seu-usuario>/projetos-programacao-web.git
   ```

2. Abra o arquivo `carrinho-compras/index.html` no navegador.

Não há dependências para instalar.

## Autor

Eduardo Rodrigo Souza Alves
