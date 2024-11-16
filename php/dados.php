<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$host = "localhost:3306";
$usuario = "root";  // Altere para o seu usuário MySQL
$senha = "";        // Altere para a sua senha MySQL
$database = "db_codequest";  // Altere para o nome do seu banco de dados

$conexao = new mysqli($host, $usuario, $senha, $database);

// Verifica a conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno']) && !isset($_SESSION['id_professor'])) {
    header("Location: login.php"); // Redireciona para a página de login se o usuário não estiver logado
    exit();
}

// Determina o ID e a tabela com base na sessão ativa
if (isset($_SESSION['id_aluno'])) {
    $id_usuario = $_SESSION['id_aluno'];  // ID do aluno
    $tabela = "aluno";  // Nome da tabela para alunos
} elseif (isset($_SESSION['id_professor'])) {
    $id_usuario = $_SESSION['id_professor'];  // ID do professor
    $tabela = "professor";  // Nome da tabela para professores
} else {
    die("Erro: Nenhuma sessão de usuário ativa.");
}

// Consulta para recuperar informações do usuário
$sql = "SELECT usuario, linkedin, github, email FROM $tabela WHERE id_{$tabela} = ?";

// Prepara e executa a consulta
if ($stmt = $conexao->prepare($sql)) {
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nome, $linkedin, $github, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

// Fecha a conexão com o banco de dados
$conexao->close();

// Passa os dados para o arquivo de exibição
$data = [
    'usuario' => $nome,
    'linkedin' => $linkedin,
    'github' => $github,
    'email' => $email
];
?>