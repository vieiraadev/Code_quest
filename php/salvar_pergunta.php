<?php
header('Content-Type: application/json');


$host = 'localhost'; 
$db = 'db_codequest';
$user = 'root'; 
$password = ''; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'Nenhum dado recebido ou dados mal formatados.',
            'debug' => file_get_contents('php://input') 
        ]);
        exit;
    }

    if (isset($data['pergunta']) && !empty(trim($data['pergunta']))) {
        $pergunta = trim($data['pergunta']);

        $stmt = $pdo->prepare("INSERT INTO perguntas (texto, data_envio) VALUES (:texto, NOW())");
        $stmt->bindParam(':texto', $pergunta, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Pergunta salva com sucesso!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao salvar a pergunta no banco de dados.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Pergunta inválida ou vazia.',
            'debug' => $data
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão com o banco de dados.',
        'debug' => $e->getMessage()
    ]);
}
?>
