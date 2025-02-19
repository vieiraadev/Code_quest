<?php
$host = 'localhost';
$db = 'db_codequest';
$user = 'root';
$password = '';

$conexao = new mysqli($host, $usuario, $senha, $database);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}
$data = json_decode(file_get_contents("php://input"), true);
$fileId = $data['id'];
$situacao = $data['situacao'];
$stmt = $conexao->prepare("UPDATE arquivos SET situacao = ? WHERE id = ?");
$stmt->bind_param("si", $situacao, $fileId);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conexao->error;
}

$stmt->close();
$conexao->close();

echo json_encode($response);
?>