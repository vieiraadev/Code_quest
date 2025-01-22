document.addEventListener('DOMContentLoaded', () => {
    const faqContainer = document.querySelector('.faq-container');
    const submitButton = document.getElementById('submit-question');
    const questionInput = document.getElementById('user-question');
    const statusMessage = document.getElementById('status-message');

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

        faqContainer.prepend(faqItem);

        const questionButton = faqItem.querySelector('.faq-question');
        questionButton.addEventListener('click', () => {
            const answers = faqItem.querySelectorAll('.faq-answer');
            answers.forEach(answer => {
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    }

    function ativarPerguntasEstaticas() {
        const staticQuestions = faqContainer.querySelectorAll('.faq-item .faq-question');
        staticQuestions.forEach(button => {
            button.addEventListener('click', () => {
                const answers = button.parentNode.querySelectorAll('.faq-answer');
                answers.forEach(answer => {
                    answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                });
            });
        });
    }

    function carregarFAQ() {
        fetch('http://localhost/code_quest/php/carregar_faq.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar perguntas.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    faqContainer.innerHTML = '';
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
    }

    function enviarPergunta() {
        const questionText = questionInput.value.trim();

        if (questionText === '') {
            statusMessage.textContent = "Por favor, digite uma pergunta.";
            statusMessage.style.color = "red";
            return;
        }

        fetch('http://localhost/code_quest/php/salvar_pergunta.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ pergunta: questionText })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusMessage.textContent = "Sua pergunta foi enviada com sucesso!";
                    statusMessage.style.color = "green";
                    questionInput.value = ''; 

                    adicionarPerguntaAoHTML(questionText);
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

    submitButton.addEventListener('click', enviarPergunta);

    ativarPerguntasEstaticas();

    carregarFAQ();
});
