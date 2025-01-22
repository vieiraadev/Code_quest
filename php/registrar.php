<?php
$host = "localhost";
$usuario = "root"; 
$senha = ""; 
$database = "db_codequest";

$conexao = new mysqli($host, $usuario, $senha, $database);


if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $github = isset($_POST['github']) ? $_POST['github'] : null;
    $linkedin = isset($_POST['linkedin']) ? $_POST['linkedin'] : null;
    $tipo_usuario = $_POST['tipo_usuario'];

    $tabela = ($tipo_usuario === "aluno") ? "alunos" : "professores";

    $stmt = $conexao->prepare("SELECT * FROM $tabela WHERE usuario = ? OR email = ?");
    $stmt->bind_param("ss", $usuario, $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
        if ($linha['usuario'] === $usuario) {
            echo "Usuário já utilizado.";
        } elseif ($linha['email'] === $email) {
            echo "Email já utilizado.";
        }
    } else {
        $stmt->close(); 

        $stmt = $conexao->prepare("INSERT INTO $tabela (usuario, senha, email, github, linkedin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $usuario, password_hash($senha, PASSWORD_DEFAULT), $email, $github, $linkedin);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conexao->close();
?>
