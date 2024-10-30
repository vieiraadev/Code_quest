<?php
$host = "localhost";
$usuario = "root"; // Usuário do MySQL (pode ser "root")
$senha = ""; // Senha do MySQL (deixe vazia se for o padrão)
$database = "db_codequest";

$conexao = new mysqli($host, $usuario, $senha, $database);

// Verifica a conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];

    // Prepara e executa a inserção no banco de dados
    $stmt = $conexao->prepare("INSERT INTO usuarios (usuario, senha, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $senha, $email);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

$conexao->close();
?>
