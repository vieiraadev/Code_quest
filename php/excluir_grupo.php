<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $grupo_id = $input['grupo_id'] ?? null;

    if ($grupo_id === null || !is_numeric($grupo_id)) {
        http_response_code(400);
        echo json_encode(["error" => "ID do grupo inválido."]);
        exit;
    }

    $sql = "DELETE FROM grupos WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $grupo_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Grupo excluído com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Erro ao excluir o grupo: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao preparar a consulta: " . $conn->error]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido."]);
}

$conn->close();
?>
