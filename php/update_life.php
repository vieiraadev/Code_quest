<?php
// Inicia a sessão
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$host = "localhost:3306";
$usuario = "root";
$senha = ""; 
$database = "db_codequest";

$conexao = new mysqli($host, $usuario, $senha, $database);

// Verificar conexão
if ($conexao->connect_error) {
    die(json_encode(["success" => false, "message" => "Erro de conexão ao banco de dados"]));
}

if (!isset($_SESSION['id_aluno'])) {
    echo json_encode(["success" => false, "message" => "Usuário não está logado"]);
    exit;
}

$aluno_id = $_SESSION['id_aluno']; 
$stmt = $conexao->prepare("CALL decrementar_vida(?)");
$stmt->bind_param("i", $aluno_id);

if ($stmt->execute()) {
    $result = $conexao->query("SELECT vida FROM aluno WHERE id_aluno = $aluno_id");
    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "new_lives" => $row['vida']]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao buscar o número atualizado de vidas"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Erro ao decrementar vida"]);
}

$stmt->close();
$conexao->close();
?>