<?php
$host = 'localhost';
$db = 'db_codequest';
$user = 'root';
$password = '';

$conexao = new mysqli($host, $usuario, $senha, $database);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$query = "SELECT id, nome_arquivo, situacao, assunto FROM arquivos";
$result = $conexao->query($query);

$files = [];
    
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['situacao'] == 'Aprovado'){
            $files[] = [
                "id" => $row['id'],
                'nome_arquivo' => $row['nome_arquivo'],
                'url' => "download.php?id=" . $row['id'],
                'assunto' => $row['assunto'] 
            ];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($files);

$conexao->close();
?>
