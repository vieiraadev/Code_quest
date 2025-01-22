<?php
header('Content-Type: application/json');
$host = 'localhost';
$db = 'db_codequest';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT id, texto, data_envio, respondida FROM perguntas ORDER BY data_envio DESC");
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($perguntas) {
        echo json_encode(['success' => true, 'data' => $perguntas]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhuma pergunta encontrada no banco.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar perguntas: ' . $e->getMessage()]);
}
?>
