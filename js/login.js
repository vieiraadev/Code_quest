document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        console.log("Erro de login detectado"); 
        alert("Login não encontrado. Verifique suas credenciais ou registre-se.");
    }
});