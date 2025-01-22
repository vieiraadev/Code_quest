<?php
session_start();
require 'conexao.php'; 


if (!isset($_SESSION['tipo_usuario']) || !isset($_SESSION['usuario_id'])) {
    header("Location: login.html"); 
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$usuario_id = $_SESSION['usuario_id'];

if ($tipo_usuario === 'aluno') {
    $query = "SELECT nome, email, linkedin, github FROM aluno WHERE id = ?";
} else {
    $query = "SELECT nome, email, linkedin, github FROM professor WHERE id = ?";
}

$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}

include 'perfil.html';
?>
