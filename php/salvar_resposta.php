<?php
header('Content-Type: application/json');

// Configuração do banco de dados
$host = 'localhost';
$db = 'db_codequest';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['pergunta_id']) && isset($data['resposta']) && !empty(trim($data['resposta']))) {
        $stmt = $pdo->prepare("INSERT INTO respostas (pergunta_id, resposta, data_resposta) VALUES (:pergunta_id, :resposta, NOW())");
        $stmt->bindParam(':pergunta_id', $data['pergunta_id'], PDO::PARAM_INT);
        $stmt->bindParam(':resposta', $data['resposta'], PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Resposta salva com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
