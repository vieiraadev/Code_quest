let lastQuestionId = 0; // Variável para controlar o ID da última pergunta respondida

document.addEventListener("DOMContentLoaded", loadQuestion);

// Função para carregar a próxima pergunta
function loadQuestion() {
  fetch(`../load_question.php?last_question_id=${lastQuestionId}`)
    .then(response => response.json())
    .then(data => {
      if (data.end) {
        alert("Parabéns! Você completou todas as questões.");
      } else {
        displayQuestion(data);
      }
    })
    .catch(error => console.error("Erro ao carregar a pergunta:", error));
}

// Função para exibir a pergunta e as alternativas na página
function displayQuestion(data) {
  document.getElementById('question-number').innerText = data.question.question_id;
  document.getElementById('question-text').innerText = data.question.question_text;
  lastQuestionId = data.question.question_id; // Atualiza o ID da última pergunta

  const answersBox = document.getElementById('answers-box');
  answersBox.innerHTML = ''; // Limpa alternativas anteriores

  data.choices.forEach(choice => {
    const button = document.createElement('button');
    button.classList.add('answer-button');
    button.innerHTML = `<span class="btn-letter">${choice.label}</span> <span class="question-answer">${choice.text}</span>`;
    button.onclick = () => submitAnswer(choice.label, button);
    answersBox.appendChild(button);
  });
}

// Função para enviar a resposta selecionada ao servidor
function submitAnswer(selectedChoice, button) {
  const questionId = document.getElementById('question-number').innerText;

  fetch('../check_answer.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ question_id: questionId, selected_choice: selectedChoice })
  })
    .then(response => response.json())
    .then(data => {
      // Se a resposta estiver correta, marca o botão como verde; caso contrário, como vermelho
      if (data.correct) {
        button.classList.add('correct'); // Adiciona classe para resposta correta (verde)
      } else {
        button.classList.add('incorrect'); // Adiciona classe para resposta incorreta (vermelho)
      }

      // Espera 1.5 segundos e carrega a próxima pergunta
      setTimeout(loadQuestion, 1500);
    })
    .catch(error => console.error("Erro ao verificar a resposta:", error));
}
