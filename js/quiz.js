const question = document.querySelector('#question');
const answerBox = document.querySelector('#answers-box');
const quizzContainer = document.querySelector('#quizz-container');
const scoreContainer = document.querySelector('#score-container');
const letters = ['a', 'b', 'c', 'd', 'e'];
let points = 0;
let actualQuestion = 0;

const questions = [
  {
    question: 'Uma forma de declarar variável em JavaScript:',
    answers: [
      {
        answer: '$var',
        correct: false,
      },
      {
        answer: 'var',
        correct: true,
      },
      {
        answer: '@var',
        correct: false,
      },
      {
        answer: '-var',
        correct: false,
      },
      {
        answer: '&var',
        correct: false,
      },
    ],
  },
  {
    question: 'Qual operador é utilizado para comparar valores e tipos em JavaScript?',
    answers: [
      {
        answer: '===',
        correct: true,
      },
      {
        answer: '!=',
        correct: false,
      },
      {
        answer: '==',
        correct: false,
      },
      {
        answer: '<>',
        correct: false,
      },
      {
        answer: '&&',
        correct: false,
      },
    ],
  },
  {
    question: 'Qual é o resultado de typeof null em JavaScript?',
    answers: [
      {
        answer: 'object',
        correct: true,
      },
      {
        answer: 'null',
        correct: false,
      },
      {
        answer: 'undefined',
        correct: false,
      },
      {
        answer: 'boolean',
        correct: false,
      },
      {
        answer: 'function',
        correct: false,
      },
    ],
  },
  {
    question: 'Qual método JavaScript converte uma string para um número inteiro',
    answers: [
      {
        answer: 'Number()',
        correct: false,
      },
      {
        answer: 'parseInt()',
        correct: true,
      },
      {
        answer: 'parseFloat()',
        correct: false,
      },
      {
        answer: 'toInteger()',
        correct: false,
      },
      {
        answer: 'convert()',
        correct: false,
      },
    ],
  },
  {
    question: 'Qual comando é utilizado para sair de um loop em JavaScript?',
    answers: [
      {
        answer: 'exit',
        correct: false,
      },
      {
        answer: 'stop',
        correct: false,
      },
      {
        answer: 'return',
        correct: false,
      },
      {
        answer: 'break',
        correct: true,
      },
      {
        answer: 'quit',
        correct: false,
      },
    ],
  },


];

function init() {
  createQuestion(0);
}

// cria uma pergunta
function createQuestion(i) {
  // limpar questão anterior
  const oldButtons = answerBox.querySelectorAll('button');
  oldButtons.forEach((btn) => {
    btn.remove();
  });
  // alterar texto da pergunta
  const questionText = question.querySelector('#question-text');
  const questionNumber = question.querySelector('#question-number');

  questionText.textContent = questions[i].question;
  questionNumber.textContent = i + 1;

  // inserir alternativas
  questions[i].answers.forEach((answer, i) => {
    // cria template botão quizz
    const answerTemplate = document.querySelector('.answer-template').cloneNode(true);

    const letterBtn = answerTemplate.querySelector('.btn-letter');
    const answerText = answerTemplate.querySelector('.question-answer');

    letterBtn.textContent = letters[i];
    answerText.textContent = answer['answer'];

    answerTemplate.setAttribute('correct-answer', answer['correct']);

    // remover hide e template class
    answerTemplate.classList.remove('hide');
    answerTemplate.classList.remove('answer-template');

    // inserir alternativa na tela
    answerBox.appendChild(answerTemplate);

    // inserir evento click no botão
    answerTemplate.addEventListener('click', function () {
      checkAnswer(this);
    });
  });

  // incrementar o número da questão
  actualQuestion++;
}

// verificar resposta do usuário
function checkAnswer(btn) {
  // seleciona todos os botões
  const buttons = answerBox.querySelectorAll('button');

  // verifica se resposta correta e add classe
  buttons.forEach((button) => {
    if (button.getAttribute('correct-answer') == 'true') {
      button.classList.add('correct-answer');

      // checa se usuário acertou a pergunta
      if (btn === button) {
        // incremento dos pontos
        points++;
      }
    } else {
      button.classList.add('wrong-answer');
    }
  });

  // exibir próxima pergunta
  nextQuestion();
}

// exibe a pŕoxima pergunta no quizz
function nextQuestion() {
  // timer para usuário ver as respostas
  setTimeout(function () {
    // verifica se ainda há perguntas
    if (actualQuestion >= questions.length) {
      // apresenta mensagem de sucesso
      showSuccessMessage();
      return;
    }

    createQuestion(actualQuestion);
  }, 1200);
}

// exibe a tela final
function showSuccessMessage() {
  hideOrShowQuizz();

  // trocar dados tela de sucesso
  // calcular score
  const score = ((points / questions.length) * 100).toFixed(2);

  const displayScore = document.querySelector('#display-score span');
  displayScore.textContent = score.toString();

  //alterar o número de perguntas corretas
  const correctAnswers = document.querySelector('#correct-answers');
  correctAnswers.textContent = points;

  // alterar o total de perguntas
  const totalQuestions = document.querySelector('#questions-qty');
  totalQuestions.textContent = questions.length;
}

// mostra ou esonde o score
function hideOrShowQuizz() {
  quizzContainer.classList.toggle('hide');
  scoreContainer.classList.toggle('hide');
}

// reiniciar quizz
const restartBtn = document.querySelector('#restart');
restartBtn.addEventListener('click', function () {
  //zerar jogo
  actualQuestion = 0;
  points = 0;
  hideOrShowQuizz();
  init();
});

// inicialização do quizz
init();
