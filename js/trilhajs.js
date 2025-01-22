let lives = 5;
document.addEventListener('DOMContentLoaded', function () {
    const progressBar = document.querySelector('.progress');
    const hearts = document.querySelectorAll('.lives i');

    let progress = 0;
    let lives = 5;


    function increaseProgress(amount) {
        if (progress < 100) {
            progress += amount;
            if (progress > 100) progress = 100;
            progressBar.style.width = progress + '%';
        }
    }


    function loseLife() {
        if (lives > 0) {
            hearts[lives - 1].classList.add('lost');
            lives--;
        } else {
            alert('Você perdeu todas as vidas!');
        }
    }


    function fetchLives() {
        fetch('../fetch_lives.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    lives = data.lives;
                    updateLivesDisplay();
                } else {
                    console.error("Erro ao buscar o número de vidas:", data.message);
                }
            })
            .catch(error => console.error("Erro ao buscar o número de vidas:", error));
    }


    function updateLivesDisplay() {
        for (let i = 1; i <= 5; i++) {
            const heart = document.getElementById(`life${i}`);
            if (i <= lives) {
                heart.classList.remove('lost');
            } else {
                heart.classList.add('lost');
            }
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        fetchLives();
    });


});

