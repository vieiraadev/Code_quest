document.addEventListener('DOMContentLoaded', () => {
    const faqContainer = document.querySelector('.faq-container');
    const submitButton = document.getElementById('submit-question');
    const questionInput = document.getElementById('user-question');
    const statusMessage = document.getElementById('status-message');

    // Função para adicionar perguntas e respostas ao HTML
    function adicionarPerguntaAoHTML(pergunta, respostas = []) {
        const faqItem = document.createElement('div');
        faqItem.classList.add('faq-item');

        let respostasHTML = '';
        respostas.forEach(resposta => {
            respostasHTML += `<p class="faq-answer" style="display: none;">${resposta}</p>`;
        });

        faqItem.innerHTML = `
            <button class="faq-question">${pergunta}</button>
            ${respostasHTML}
        `;

        faqContainer.appendChild(faqItem);

        // Adiciona evento de clique para expandir/ocultar respostas
        const questionButton = faqItem.querySelector('.faq-question');
        questionButton.addEventListener('click', () => {
            const answers = faqItem.querySelectorAll('.faq-answer');
            answers.forEach(answer => {
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    }

    // Função para carregar perguntas e respostas do servidor
    function carregarFAQ() {
        fetch('http://localhost/code_quest/php/carregar_faq.php') // Certifique-se de que o caminho está correto
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar perguntas.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    data.data.forEach(item => {
                        adicionarPerguntaAoHTML(item.pergunta, item.respostas || []);
                    });
                } else {
                    console.error('Erro ao carregar FAQ:', data.message);
                }
            })
            .catch(error => {
                console.error('Erro na conexão:', error);
            });
        function enviarPergunta() {
            const questionText = questionInput.value.trim();

            if (questionText === '') {
                statusMessage.textContent = "Por favor, digite uma pergunta.";
                statusMessage.style.color = "red";
                return;
            }

            fetch('http://localhost/code_quest/php/salvar_pergunta.php', { // Caminho do PHP para salvar perguntas
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ pergunta: questionText }) // Envia o texto da pergunta
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        statusMessage.textContent = "Sua pergunta foi enviada com sucesso!";
                        statusMessage.style.color = "green";
                        questionInput.value = ''; // Limpa o campo de entrada
                    } else {
                        statusMessage.textContent = data.message || "Erro ao enviar a pergunta.";
                        statusMessage.style.color = "red";
                    }
                })
                .catch(error => {
                    console.error('Erro na conexão:', error);
                    statusMessage.textContent = "Erro na conexão. Tente novamente.";
                    statusMessage.style.color = "red";
                });
        }

        // Adiciona evento ao botão "Enviar"
        submitButton.addEventListener('click', enviarPergunta);

    }

    // Carrega as perguntas quando a página é carregada
    carregarFAQ();
});
