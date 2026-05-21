function gerarCampos() { // Função de criação dos campos dos alunos de acordo com a quantidade definida no site
            let qtd = document.getElementById("qtd").value;
            let area = document.getElementById("camposAlunos");

            area.innerHTML = "";

            for (let i = 1; i <= qtd; i++) {
                area.innerHTML += `
                    <fieldset>
                        <legend>Aluno ${i}</legend>

                        <label>Nome:</label>
                        <input type="text" name="nome[]" required>

                        <label>Nota Prova 1:</label>
                        <input type="number" step="0.1" name="nota1[]" required>

                        <label>Nota Prova 2:</label>
                        <input type="number" step="0.1" name="nota2[]" required>

                        <label>Nota Trabalho:</label>
                        <input type="number" step="0.1" name="trabalho[]" required>
                    </fieldset>
                    <br>
                `;
            }
        }