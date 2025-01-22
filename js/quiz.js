let lastQuestionId = 0;
let lives = 5;

document.addEventListener("DOMContentLoaded", () => {
  fetchLives();
  loadQuestion();
});


function fetchLives() {
  fetch('/code_quest/php/fetch_lives.php')
    .then(response => response.json())
    .then(data => {
      lives = data.lives;
      updateLivesDisplay();
    })
    .catch(error => console.error("Erro ao buscar o número de vidas:", error));
}


function loadQuestion() {
  fetch(`/code_quest/php/load_question.php?last_question_id=${lastQuestionId}`)
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

function displayQuestion(data) {
  document.getElementById('question-number').innerText = data.question.question_id;
  document.getElementById('question-text').innerText = data.question.question_text;
  lastQuestionId = data.question.question_id;
  const answersBox = document.getElementById('answers-box');
  answersBox.innerHTML = '';

  data.choices.forEach(choice => {
    const button = document.createElement('button');
    button.classList.add('answer-button');
    button.innerHTML = `<span class="btn-letter">${choice.label}</span> <span class="question-answer">${choice.text}</span>`;
    button.onclick = () => submitAnswer(choice.label, button);
    answersBox.appendChild(button);
  });
}

function submitAnswer(selectedChoice, button) {
  const questionId = document.getElementById('question-number').innerText;

  const answerButtons = document.querySelectorAll('.answer-button');
  answerButtons.forEach(btn => btn.disabled = true);

  fetch('/code_quest/php/check_answer.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ question_id: questionId, selected_choice: selectedChoice })
  })
    .then(response => response.json())
    .then(data => {
      if (data.correct) {
        button.classList.add('correct');
      } else {
        button.classList.add('incorrect');
        loseLife();
      }

      setTimeout(loadQuestion, 1500);
    })
    .catch(error => console.error("Erro ao verificar a resposta:", error));
}

function loseLife() {
  if (lives > 0) {
    lives -= 1;
    updateLivesDisplay();

    fetch('/code_quest/php/update_life.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ decrement: 1 })
    })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          console.error("Erro ao atualizar vida no banco:", data.message);
        }
      })
      .catch(error => console.error("Erro ao atualizar vida:", error));


    if (lives === 0) {
      alert("Você perdeu todas as vidas! Tente novamente.");
      disableQuiz();
    }
  }
}

function updateLivesDisplay() {
  console.log("Atualizando exibição das vidas. Total de vidas:", lives);
  for (let i = 1; i <= 5; i++) {
    const heart = document.getElementById(`life${i}`);
    if (heart) {
      console.log(`Alterando estado do coração com ID life${i}`);
      heart.classList.toggle('lost', i > lives);
    } else {
      console.error(`Elemento com ID life${i} não encontrado.`);
    }
  }
}



function disableQuiz() {
  const answerButtons = document.querySelectorAll('.answer-button');
  answerButtons.forEach(btn => btn.disabled = true);
}