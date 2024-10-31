<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão
session_start();

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

    // Consulta para verificar se o usuário existe no banco de dados
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $user = $resultado->fetch_assoc();

        // Verifica se a senha está correta
        if (password_verify($senha, $user['senha'])) {
            // Login bem-sucedido, inicia sessão para o usuário
            $_SESSION['usuario'] = $usuario;
            echo "Login realizado com sucesso!"; // Redirecione para a área interna do sistema, se necessário
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não possui uma conta registrada.";
    }

    $stmt->close();
}

$conexao->close();
?>
