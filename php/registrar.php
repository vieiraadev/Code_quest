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
    $github = isset($_POST['github']) ? $_POST['github'] : null;
    $linkedin = isset($_POST['linkedin']) ? $_POST['linkedin'] : null;
    $tipo_usuario = $_POST['tipo_usuario'];

    // Verifica se o nome de usuário ou o email já existem na tabela apropriada
    $tabela = ($tipo_usuario === "aluno") ? "alunos" : "professores";

    $stmt = $conexao->prepare("SELECT * FROM $tabela WHERE usuario = ? OR email = ?");
    $stmt->bind_param("ss", $usuario, $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Verifica se o conflito é de nome de usuário ou email
        $linha = $resultado->fetch_assoc();
        if ($linha['usuario'] === $usuario) {
            echo "Usuário já utilizado.";
        } elseif ($linha['email'] === $email) {
            echo "Email já utilizado.";
        }
    } else {
        // Prepara e executa a inserção na tabela correspondente
        $stmt->close(); // Fecha a consulta anterior

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
