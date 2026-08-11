// Confirmação antes de deletar
function confirmarExclusao(e, nome) {
    if (!confirm(`Tem certeza que deseja excluir "${nome}"?`)) {
        e.preventDefault();
    }
}

// Filtro/Pesquisa dinâmica na tabela
document.addEventListener('DOMContentLoaded', () => {
    const inputBusca = document.getElementById('busca');
    if (inputBusca) {
        inputBusca.addEventListener('keyup', () => {
            const termo = inputBusca.value.toLowerCase();
            const linhas = document.querySelectorAll('tbody tr');
            linhas.forEach(linha => {
                const texto = linha.textContent.toLowerCase();
                linha.style.display = texto.includes(termo) ? '' : 'none';
            });
        });
    }
});