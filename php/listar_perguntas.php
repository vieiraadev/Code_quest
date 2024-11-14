<?php
header('Content-Type: application/json');

// Configuração do banco de dados
$host = 'localhost';
$db = 'db_codequest';
$user = 'root';
$password = '';

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter todas as perguntas
    $stmt = $pdo->query("SELECT id, texto, data_envio FROM perguntas ORDER BY data_envio DESC");
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Depuração: Verifique se a consulta retornou resultados
    if ($perguntas) {
        echo json_encode(['success' => true, 'data' => $perguntas]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhuma pergunta encontrada no banco.']);
    }
} catch (PDOException $e) {
    // Retorna erros do banco de dados
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar perguntas: ' . $e->getMessage()]);
}
?>
