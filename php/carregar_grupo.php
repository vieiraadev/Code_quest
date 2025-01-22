<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$usuario = "root";
$senha = "";
$database = "db_codequest";

$conn = new mysqli($host, $usuario, $senha, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao conectar ao banco de dados."]);
    exit;
}

$sql = "SELECT id, nome_grupo, descricao, max_integrantes FROM grupos";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $grupos = [];
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }
    echo json_encode($grupos);
} else {
    echo json_encode([]);
}

$conn->close();
?>
