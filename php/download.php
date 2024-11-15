<?php
$host = 'localhost';
$db = 'db_codequest';
$user = 'root';
$password = '';

$conexao = new mysqli($host, $usuario, $senha, $database);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta o arquivo no banco
    $stmt = $conexao->prepare("SELECT nome_arquivo, dados FROM arquivos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        // Define os headers para download
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . $file['nome_arquivo'] . "\"");

        // Exibe o conteúdo do blob
        echo $file['dados'];
    } else {
        echo "Arquivo não encontrado.";
    }
} else {
    echo "ID do arquivo não foi especificado.";
}

?>