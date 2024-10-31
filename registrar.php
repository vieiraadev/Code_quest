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

    // Verifica se o nome de usuário já existe
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Usuário já existe
        echo "Este nome de usuário já está em uso. Tente outro.";
    } else {
        // Prepara e executa a inserção no banco de dados
        $stmt->close(); // Fecha a consulta anterior

        $stmt = $conexao->prepare("INSERT INTO usuarios (usuario, senha, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, password_hash($senha, PASSWORD_DEFAULT), $email); // Criptografa a senha

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
