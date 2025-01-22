<?php
session_start();

$host = "localhost:3306";
$usuario = "root";  
$senha = "";   
$database = "db_codequest";

$conexao = new mysqli($host, $usuario, $senha, $database);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_professor'])) {
    header("Location: login.php"); 
    exit();
}


if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno']; 
    $tabela = "aluno";  
} elseif (isset($_SESSION['id_professor'])) {
    $id_usuario = $_SESSION['id_professor']; 
    $tabela = "professor";  
} else {
    die("Erro: Nenhuma sessão de usuário ativa.");
}


$sql = "SELECT usuario, linkedin, github, email FROM $tabela WHERE id_{$tabela} = ?";


if ($stmt = $conexao->prepare($sql)) {
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nome, $linkedin, $github, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

$conexao->close();

$data = [
    'usuario' => $nome,
    'linkedin' => $linkedin,
    'github' => $github,
    'email' => $email
];
?>