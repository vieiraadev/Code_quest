<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

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
    
    function verificar_usuario($conexao, $usuario, $senha, $tabela) {
        $stmt = $conexao->prepare("SELECT * FROM $tabela WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                return true; 
            } else {
                return false;
            }
        }
        return false;
    }
    
    if (verificar_usuario($conexao, $usuario, $senha, "alunos") || verificar_usuario($conexao, $usuario, $senha, "professores")) {
        $_SESSION['usuario'] = $usuario;
        header("Location: /code_quest/html/modulos.html");
        exit();
    } else {
        header("Location: /code_quest/html/login.html?error=1");
        exit();
    }
}

$conexao->close();
?>
