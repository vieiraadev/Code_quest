document.addEventListener('DOMContentLoaded', function () {
    const progressBar = document.querySelector('.progress');
    const hearts = document.querySelectorAll('.lives i');

    let progress = 0; // Progresso inicial
    let lives = 5;    // Vidas iniciais

    // Função para aumentar a barra de progresso
    function increaseProgress(amount) {
        if (progress < 100) {
            progress += amount;
            if (progress > 100) progress = 100; // Limite de 100%
            progressBar.style.width = progress + '%';
        }
    }

    // Função para perder uma vida
    function loseLife() {
        if (lives > 0) {
            hearts[lives - 1].classList.add('lost');
            lives--;
        } else {
            alert('Você perdeu todas as vidas!');
        }
    }

});
