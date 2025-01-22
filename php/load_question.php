<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$database = "db_codequest";

$conn = new mysqli($host, $usuario, $senha, $database);

$last_question_id = isset($_GET['last_question_id']) ? intval($_GET['last_question_id']) : 0;

$sql_question = "SELECT id AS question_id, question_text 
                 FROM questions 
                 WHERE id > ? AND module_id = 1 
                 ORDER BY id ASC 
                 LIMIT 1";
                 
$stmt_question = $conn->prepare($sql_question);
$stmt_question->bind_param("i", $last_question_id);
$stmt_question->execute();
$result_question = $stmt_question->get_result();

if ($question_row = $result_question->fetch_assoc()) {
    $question = [
        "question_id" => $question_row["question_id"],
        "question_text" => $question_row["question_text"]
    ];

    $sql_choices = "SELECT choice_text, choice_label 
                    FROM choices 
                    WHERE question_id = ?";
                    
    $stmt_choices = $conn->prepare($sql_choices);
    $stmt_choices->bind_param("i", $question["question_id"]);
    $stmt_choices->execute();
    $result_choices = $stmt_choices->get_result();

    $choices = [];
    while ($choice_row = $result_choices->fetch_assoc()) {
        $choices[] = [
            "label" => $choice_row["choice_label"],
            "text" => $choice_row["choice_text"]
        ];
    }

    echo json_encode([
        "question" => $question,
        "choices" => $choices
    ]);
} else {

    echo json_encode(["end" => true]);
}

$conn->close();
?>
