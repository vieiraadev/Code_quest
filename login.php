<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão
session_start();

$host = "localhost";
$usuario = "root";
$senha = ""; 
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
    
    // Função para verificar o usuário em uma tabela específica
    function verificar_usuario($conexao, $usuario, $senha, $tabela) {
        $stmt = $conexao->prepare("SELECT * FROM $tabela WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                // Retorna verdadeiro se o login foi bem-sucedido
                return true;
            } else {
                echo "Senha incorreta.";
                return false;
            }
        }
        return false;
    }
    
    // Verifica nas tabelas alunos e professores
    if (verificar_usuario($conexao, $usuario, $senha, "alunos")) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipo_usuario'] = "aluno";
        echo "Login realizado com sucesso como Aluno!";
    } elseif (verificar_usuario($conexao, $usuario, $senha, "professores")) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipo_usuario'] = "professor";
        echo "Login realizado com sucesso como Professor!";
    } else {
        echo "Usuário não possui uma conta registrada.";
    }
}

$conexao->close();
?>
