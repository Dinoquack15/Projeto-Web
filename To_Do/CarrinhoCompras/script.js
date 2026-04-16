const listaItems = document.querySelector("#listaItems");
const itensCarrinho = document.querySelector("#itensCarrinho");
const filtro = document.querySelector("#filtro");
const totalSpan = document.querySelector("#total");

const produtos = [ // Lista dos produtos com preço, para adicionar mais produtos só adicionar o nome e preço
    { nome: "Arroz", preco: 25.90 },
    { nome: "Feijão", preco: 12.50 },
    { nome: "Leite", preco: 6.99 },
    { nome: "Sabão", preco: 8.00 },
    { nome: "Chocolate", preco: 15.00 },
    // acima de 50
    { nome: "Carne", preco: 55.00 },
    { nome: "Café Premium", preco: 62.00 },
    { nome: "Azeite de Oliva", preco: 75.90 },
    { nome: "Queijo", preco: 58.50 },
    { nome: "Kit Churrasco", preco: 120.00 }
];

let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

function salvarCarrinho() {
    localStorage.setItem("carrinho", JSON.stringify(carrinho)); // Armazena o carrinho no localstorage
}

function formatarPreco(valor) { // Formata dinheiro para apenas duas casas
    return valor.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
    });
}

function filtrarProdutos() { // Filtra os produtos com base no valor selecionado no dropdown
    const valorFiltro = filtro.value;
    let produtosFiltrados = [];
    switch (valorFiltro) {
        case "ate50":
            produtosFiltrados = produtos.filter(p => p.preco <= 50);
            break;
        case "acimade50":
            produtosFiltrados = produtos.filter(p => p.preco > 50);
            break;
        default:
            produtosFiltrados = produtos;
            break;
    } 

    listaItems.innerHTML = "";

    produtosFiltrados.forEach(produto => { // Cria um elemento html pra cada produto no array
        const div = document.createElement("div");
        div.classList.add("produto");

        // Usa a função de formatação pra exibir o valor formatado
        div.innerHTML = `
                <p><strong>${produto.nome}</strong></p>
                <p>${formatarPreco(produto.preco)}</p>
            `;

        const botao = document.createElement("button");
        botao.textContent = "Adicionar ao carrinho";
        botao.addEventListener("click", () => adicionarAoCarrinho(produto));

        div.appendChild(botao);
        listaItems.appendChild(div);
    });
}

function adicionarAoCarrinho(produto) { // função de adicionar itens ao carrinho
    const itemExistente = carrinho.find(item => item.nome === produto.nome);

    if (itemExistente) {  // Verifica se o item já está no carrinho, se estiver ele adiciona 1 na quantidade, se não ele adiciona o item pra evitar duplicação
        itemExistente.quantidade += 1;
    } 
    else {
        carrinho.push({
            nome: produto.nome,
            preco: produto.preco,
            quantidade: 1
        });
    }

    salvarCarrinho();
    atualizarCarrinho();
}

function removerDoCarrinho(nomeProduto) { // Faz o processo inverso da adição: diminui a quantidade ou remove o item se não tiver mais como decrementar
    const item = carrinho.find(item => item.nome === nomeProduto);

    if (item) {
        if (item.quantidade > 1) {
            item.quantidade -= 1;
        } else {
            carrinho = carrinho.filter(item => item.nome !== nomeProduto);
        }
    }

    salvarCarrinho();
    atualizarCarrinho();
}

function atualizarCarrinho() {
    itensCarrinho.innerHTML = "";

    if (carrinho.length === 0) { // Se não tem itens mostra que o carrinho está vazio
        itensCarrinho.innerHTML = "<p class='vazio'>Carrinho vazio</p>";
        totalSpan.textContent = "Total: R$ 0,00";
        return;
    }

    let total = 0;

    carrinho.forEach(item => { // Faz o calculo do valor total de cada item e depois soma tudo no total do carrinho
        const totalItem = item.preco * item.quantidade;
        total += totalItem;

        const div = document.createElement("div");
        div.classList.add("item-carrinho");

        div.innerHTML = `
                <p><strong>${item.nome}</strong></p>
                <p>Quantidade: ${item.quantidade}</p>
                <p>Total: ${formatarPreco(totalItem)}</p>
            `;

        const botaoRemover = document.createElement("button");
        botaoRemover.textContent = "Remover";
        botaoRemover.addEventListener("click", () => removerDoCarrinho(item.nome));

        div.appendChild(botaoRemover);
        itensCarrinho.appendChild(div);
    });

    totalSpan.textContent = `Total: ${formatarPreco(total)}`;
}

filtro.addEventListener("change", filtrarProdutos);

filtrarProdutos();
atualizarCarrinho();